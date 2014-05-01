curl http://localhost:8080/solr/volumes/update?commit=true -H "Content-Type: text/xml" --data-binary '<delete><query>*:*</query></delete>'

