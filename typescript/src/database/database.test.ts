import { Database } from "./database"
import { existsSync, readFileSync, rmdirSync } from "fs"
import * as Faker from "faker"

describe("Database suite", () => {
  beforeAll(() => {
    if(existsSync(".database-temp")){
      rmdirSync(".database-temp", {recursive: true})
    }
  })

  test("Check database creation", () => {
    const db = new Database("mock-test")

    expect(db).toBeTruthy()
    expect(existsSync(db.getInfo().local)).toBeTruthy()
  })

  test("Check add feature", () => {
    const db = new Database("mock-create-test")
    const elements: number[] = [1, 2, 3, 4, 5]

    elements.forEach(() => {
      db.add({
        name: Faker.name.firstName(),
        age: Faker.random.number(80),
        email: Faker.internet.email()
      })
    })

    const content = readFileSync(db.getInfo().local, "utf-8")

    JSON.parse(content).data.forEach((element: any) => {
      expect("name" in element).toBe(true)
      expect("age" in element).toBe(true)
      expect("email" in element).toBe(true)
    })
  })

  test("Check getAll feature", () => {
    const db = new Database("mock-read-test")
    const elements: number[] = [1, 2, 3]

    elements.forEach(() => {
      db.add({
        name: Faker.name.firstName(),
        age: Faker.random.number(80),
        email: Faker.internet.email()
      })
    })

    const content = db.getAll()
    expect(content).toBeTruthy()
  })

  test("Check getInfo feature", () => {
    const db = new Database("mock-info-test")

    expect(db.getInfo().path).toBe(".database-temp")
    expect(db.getInfo().format).toBe(".json")
    expect(db.getInfo().local).toBe(".database-temp/mock-info-test.json")
    expect(db.getInfo().name).toBe("mock-info-test")
  })

  test("Check query feature", () => {
    const db = new Database("mock-search-test")

    db.add({name: "foo"})
    db.add({name: "bar"})
    db.add({name: "span"})
    db.add({name: "span"})
    db.add({name: "fiz"})

    const result1 = db.query((item: any) => item.name === "span")
    const result2 = db.query((item: any) => item.name === "bar")
    const result3 = db.query((item: any) => item.name === "undefined")

    expect(result1).toBeTruthy()
    expect(result1.length).toBe(2)
    result1.forEach((element: any) => {
      expect(element.name).toBe("span")
    })

    expect(result2).toBeTruthy()
    expect(result2.name).toBe("bar")

    expect(result3).toBeFalsy()
  })

  test("Check update feature", () => {
    const db = new Database("mock-update-test")

    db.add({ name: "foo", id: 1 })
    db.add({ name: "bar", id: 2 })
    db.add({ name: "span", id: 3 })
    db.add({ name: "egg", id: 4 })

    db.update(1, {
      name: "FOO UPDATED"
    })

    db.update(3, {
      name: "SPAN UPDATED"
    })

    const fooUpdated = db.query((item: any) => item.id === 1)
    const spanUpdated = db.query((item: any) => item.id === 3)

    expect(fooUpdated.name).toBe("FOO UPDATED")
    expect(spanUpdated.name).toBe("SPAN UPDATED")
  })

  test("Check delete feature", () => {
    const db = new Database("mock-delete-test")

    db.add({ name: "foo", id: 1 })
    db.add({ name: "bar", id: 2 })
    db.add({ name: "span", id: 3 })
    db.add({ name: "egg", id: 4 })
    db.add({ name: "fux", id: 5 })
    db.add({ name: "max", id: 6 })

    db.delete(1)
    db.delete(4)

    const content = db.getAll()
    const element1 = db.query((item: any) => item.id === 1)
    const element2 = db.query((item: any) => item.id === 4)

    expect(content.data.length).toBe(4)
    expect(element1).toBeFalsy()
    expect(element2).toBeFalsy()
  })
})
