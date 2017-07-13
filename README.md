# gooduncle-columnar-db

<H1>php implementation for a columnar datastore</h1>

this uses mysql style syntax and all commands are case insensitive:

<H2> SHOW databases</H2>
```list databases; ```

<h2>USE nameOfDatabase</h2>
```use ErwinDB; ```


<h2>create table </h2>
```create tableName(first_name varchar(27), age int(10));```



<h2>insert table</h2>
```insert into tableName(first_name, age) values('Erwin',1000);```


<h2>update table </h2>
```update tableName set first_name='Bob' where first_name ='Erwin'; ```


<h2>select * From table</h2>
```select * from users where first_name ='Erwin'; ```


<h2>select fields from table</h2>
```select first_name, age from users where first_name ='Erwin'; ```



<h2>delete  table</h2>
```delete from users where first_name ='Erwin'; ```



