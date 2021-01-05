<p align="center">
  <img alt="react" src="https://cdn.icon-icons.com/icons2/2334/PNG/512/box_cube_d_perspective_shape_icon_142362.png" width="100" />
</p>

<h1 align="center">
  Ex-MDB
</h1>

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

c4co - 2020
