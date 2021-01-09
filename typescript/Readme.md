# EX-MDB - Typescript

## Usage

Create Database
```typescript
import { Database } from "./src/database/database"
const db = new Database("my-db");
```

Add data

```add(data: object): this```

```typescript
db.add({ name: "foo" })
  .add({ name: "bar" })
  .add({ name: "span" })
  .add({ name: "egg" })
  .add({ name: "fux" })
  .add({ name: "max" })
```

Get all data

```getAll(): {data: Array}```
```typescript
const allData = db.getAll();
```

Query

```query(condition: Function): object```
```typescript
  const result1 = db.query((item: any) => item.name === "span")
  const result2 = db.query((item: any) => item.name === "bar")
  const result3 = db.query((item: any) => item.name === "undefined")
```

Update

```update(id: number | string, item: object): boolean```
```typescript
db.update(1, {
  name: "FOO UPDATED"
})

db.update(3, {
  name: "SPAN UPDATED"
})
```
