import MySQLdb
import json
co=MySQLdb.connect("localhost","user","pwd","wikidata")
con=co.cursor()
f=open("errorlogwikidataidparse.log","a")
tr=0
with open("/data/wiki.json") as f1:
	for i in f1:
		try:
			c=eval(i)
			if str(c[0]["id"]).startswith("Q"):
				#print c[0]["id"]
				#print c[0]["labels"]["en"]["value"]
				#f.write(c[0])
				t=filter(lambda x:ord(x)>31 and ord(x)<128,c[0]["labels"]["en"]["value"])
				sql="INSERT INTO `wiki_title` VALUES('%s','%s');"%(str(c[0]["id"]),str(t))
				con.execute(sql)
				co.commit()
				tr=tr+1
		except Exception as e:
			pass
print tr
