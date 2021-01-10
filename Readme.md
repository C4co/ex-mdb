# Ex-MDB

Just writing some code.

- Record data in ```.json``` format
- Features: write, read, update, delete and query

### Interface

```typescript
  interface database {
    public getInfo(): Object //info about database
    public add(item: Object): Boolean //add new item in database
    public getAll(): { data: Array } //getall data
    public query(condition: function): Array //search in database
    public update(id: String, item: Object): Boolean //update an item
    public delete(id: String): Boolean //delete an item
  }
```

### Source

- [PHP](https://github.com/C4co/ex-mdb/tree/master/php)
- [Typescript](https://github.com/C4co/ex-mdb/tree/master/typescript)

---

<p align="center">
  C4co - 2020
</p>
