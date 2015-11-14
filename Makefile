clean:
	curl -XDELETE 127.0.0.1:9200/*

logstash:
	logstash -f logstash.conf
