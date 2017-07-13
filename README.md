# gooduncle-columnar-db

<H1>php implementation for a columnar datastore</h1>

this uses mysql style syntax and all commands are case insensitive:

<H2> SHOW databases</H2>
```sql
list databases; 
```

<h2>USE nameOfDatabase</h2>
```sql
use ErwinDB; 
```


<h2>create table </h2>
```sql
create tableName(first_name varchar(27), age int(10));
```



<h2>insert table</h2>
```sql
insert into tableName(first_name, age) values('Erwin',1000);
```


<h2>update table </h2>
```sql
update tableName set first_name='Bob' where first_name ='Erwin'; 
```


<h2>select * From table</h2>
```sql
select * from users where first_name ='Erwin'; 
```


<h2>select fields from table</h2>
```sql
select first_name, age from users where first_name ='Erwin'; 
```



<h2>delete  table</h2>
```sql
delete from users where first_name ='Erwin'; 
```



