from tkinter import *
from tkinter import messagebox
from tkinter import simpledialog
from datetime import datetime, timedelta
import sqlite3
import os

##################################
def addStops(n):
    global stopList, frame
    for x in range(0, n):
        stopList.append([Entry(frame), Entry(frame), Entry(frame)])
#################################
def addStop():
    global frame
    addStops(1)
    buildFrame(frame)
#################################
def saveStops():
    global stopList, tId, tVon, tNach
    cur = dbase.cursor()

    if(tID.get() == ''):
        messagebox.showerror(title=None, message="Es wurde keine Zugnummer angegeben!")
        return

    print("Zug {} von {} nach {}".format(tID.get(), tVon.get(), tNach.get()))
    cur.execute("INSERT INTO Trains (ID, Von, Nach) VALUES(?, ?, ?)", (tID.get(), tVon.get(), tNach.get(),))
    dbase.commit()

    for i in stopList:
        if(not i[0].get() == ''):
            print("{}, {}, {}, {}".format(tID.get(), i[0].get(), i[1].get(), i[2].get()))
            cur.execute("INSERT INTO Stops (TID, SR100, Arrival, Departure) VALUES(?, ?, ?, ?)", (tID.get(), i[0].get(), i[1].get(), i[2].get(),))
            dbase.commit() 
#################################
def clearStops():
    global stopList, tID

    tID.delete(0, END)

    for i in stopList:
        if(not i[0].get() == ''):
            i[1].delete(0, END)
            i[2].delete(0, END)

    stopList[0][1].insert(-1, "START")
    stopList[-1][2].insert(-1, "END")
##################################
def calcTimes():
    global stopList

    minute = timedelta(minutes=1)

    for i in stopList:
        if(not i[0].get() == ''):
            if(i[2].get() == ''):
                if(i[1].get() == ''):
                    continue
                i[2].insert(-1, (datetime.strptime(i[1].get(), '%H:%M') + minute).strftime('%H:%M'))
##################################
def loadRoute():
    global stopList, tVon, tNach, stops

    route = simpledialog.askstring(title="Route", prompt="Gebe Routen-ID ein:")
    if route == '':
        return
    
    cur = dbase.cursor()
    cur.execute("SELECT SR100 FROM Waypoints WHERE ROID=?", (route,))
    rows = cur.fetchall()

    stops = rows
    
    if (len(stopList) < len(rows)):
        print("Mehr Rows!")
        print("Differenz: {}".format(len(rows) - len(stopList)))
        diff = len(rows) - len(stopList)
        
        addStops(diff)
        buildFrame(frame)
        
    if(len(stopList) > len(rows)):
        print("Weniger Rows!")
        print("Differenz: {}".format(len(rows) - len(stopList)))

        stopList = []
        resetFrame()
        addStops(len(rows))        
        buildFrame(frame)
    
    x = 0
    for i in stopList:
        i[0].delete(0, END)
        i[0].insert(-1, rows[x])
        #print(rows[x])
        x = x + 1

    cur.execute("SELECT * FROM Routes WHERE ID=?", (route,))
    rows = cur.fetchall()
    tVon.delete(0, END)
    tNach.delete(0, END)

    for row in rows:
        tVon.insert(-1, row[2])
        tNach.insert(-1, row[3])

    stopList[0][1].insert(-1, "START")
    stopList[-1][2].insert(-1, "END")
##################################
def reverseRoute():
    global stops, tVon, tNach, stopList

    x = 0
    for i in reversed(stops):
        stopList[x][0].delete(0, END)
        stopList[x][0].insert(-1, i)

        x = x + 1

    stops.reverse()

    y = tVon.get()
    tVon.delete(0, END)
    tVon.insert(-1, tNach.get())
    tNach.delete(0, END)
    tNach.insert(-1, y)
##################################
def buildFrame(frame):
    global stopList
    global tID, tVon, tNach

    ti = Label(frame, text="Zugnummer:")
    tv = Label(frame, text="Von:")
    tn = Label(frame, text="Nach:")

    tStop = Label(frame, text="Station:")
    tArr = Label(frame, text="Ankunft:")
    tDep = Label(frame, text="Abfahrt:")

    bAdd = Button(frame, text="Stopp hinzufügen", command=addStop)
    bSend = Button(frame, text="Speichern", command=saveStops)
    bClear = Button(frame, text="Werte löschen", command=clearStops)
    bLoad = Button(frame, text="Lade Strecke", command=loadRoute)
    bCalc = Button(frame, text="Fehlende Zeiten berechnen", command=calcTimes)
    bRev = Button(frame, text="Strecke umkehren", command=reverseRoute)

    tID = Entry(frame)
    tVon = Entry(frame)
    tNach = Entry(frame)

    ti.grid(row=1, column=0)
    tID.grid(row=2, column=0)
    tv.grid(row=1, column=1)
    tVon.grid(row=2, column=1)
    tn.grid(row=1, column=2)
    tNach.grid(row=2, column=2)
    tStop.grid(row=3, column=0)
    tArr.grid(row=3, column=1)
    tDep.grid(row=3, column=2)
    bAdd.grid(row=0, column=2)
    bSend.grid(row=999, column=2)
    bClear.grid(row=999, column=0)
    bCalc.grid(row=999, column=1)
    bLoad.grid(row=0, column=0)
    bRev.grid(row=0, column=1)

    y = 4
    x = 0
    for i in stopList:
        for u in i:
            u.grid(row=y, column=x)
            x = x + 1
        y = y + 1
        x = 0
    
##################################
def resetFrame():
    global frame
    frame.destroy()
    frame = Label(root)
    frame.grid()
##################################
root = Tk()
tID = None
tVon = None
tNach = None
dbase = sqlite3.connect(os.path.join('..','Reichsbahn.db3'))

stopList=[]
stops=[]

frame = Frame(root)
buildFrame(frame)

frame.pack()

if __name__ == '__main__':
    root.mainloop()
