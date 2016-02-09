import urllib2
import time
import os,sys
from bs4 import BeautifulSoup
import json
from log import putmsg,headmsg

headmsg("starting wikidata dump","info")
putmsg("starting wikidata dump","info")

#reading lastupdated data from stats.json
try:
	f=open("stats.json","r+")
	data=f.read()
	data=json.loads(data)
	putmsg("stats.json loaded","success")

	g=open("nconfig.json","r")
	config=g.read()
	config=json.loads(config)
	putmsg("config.json loaded","success")
except Exception as e:
	putmsg("stats.json or config.json is missing :: "+str(e),"error")
	system.exit(0)

#retrieving system date in required format
t_date=int(time.strftime("20%y%m%d"))
rdate=time.strftime("%d-%m-%y")

#retrieving date from headers of dump url
try:
	url="https://dumps.wikimedia.org/wikidatawiki/entities/latest-all.json.bz2"
	response=urllib2.urlopen(url)
	response_headers=response.info().dict
	lastupdate=response_headers["last-modified"].strip()
except Exception as e:
	putmsg("cannot read url::"+str(e),"error")

putmsg("extracted headers","success")
if lastupdate !=data["lastupdate"]:
	putmsg("performing update operation","info")
	try:
		putmsg("downloading dump","info")
		os.system("wget "+url)
	except Exception as e:
		putmsg("error in downloading :: "+str(e),"error")
		sys.exit(0)
	data["lastupdate"]=lastupdate
	f.write(data)

	try:
		filename="latest-all.json.bz2"
		putmsg("download complete\ndecompressing","info")
		os.system("bzip2 -d "+filename)
		putmsg("decompression complete\n\nbeginning to dump using api","info")
		os.system("php entitystore import-json-dump "+nconfig["dump_location"]+" config.json")
	except Exception as e:
		putmsg(str(e),"error")
		headmsg(str(e),"error")
		sys.exit(0)

else:
	putmsg("data already updated","info")
	headmsg("data already updated","info")
	sys.exit(0)