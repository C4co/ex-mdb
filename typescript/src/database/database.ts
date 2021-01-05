import { writeFileSync, readFileSync, existsSync, mkdirSync } from "fs"
import { Utils } from "../utils/utils"

export class Database {
  private _databasePath: string = ".database-temp"
  private _databaseName: string
  private _databaseFormat: string = ".json"
  private _databaseLocal: string

  public constructor(databaseName: string){
    this._databaseName = `${databaseName}`
    this._databaseLocal = `${this._databasePath}/${this._databaseName}${this._databaseFormat}`
    this.checkDatabase()
  }

  public getInfo(){
    return {
      path: this._databasePath,
      name: this._databaseName,
      format: this._databaseFormat,
      local: this._databaseLocal
    }
  }

  private checkDatabase(){
    try{
      if(!existsSync(this._databasePath)){
        mkdirSync(this._databasePath)
      }

      if(!existsSync(this._databaseLocal)){
        writeFileSync(this._databaseLocal, JSON.stringify({"data": []}))
      }
    }catch(error){
      console.log("Check database", error.message)
    }
  }

  public add(element: any){
    if(!element.id){
      element.id = Utils.ID()
    }

    try {
      let database: any = readFileSync(this._databaseLocal, "utf-8")
      database = JSON.parse(database)
      database = Array(...database.data)

      const databaseUpdated = {"data": database.concat(element)}
      writeFileSync(this._databaseLocal, JSON.stringify(databaseUpdated, null , 2))

      return this
    } catch (error) {
      console.log("Database create", error.message)
    }
  }

  public getAll(){
    try {
      const database = readFileSync(this._databaseLocal, "utf-8")
      return JSON.parse(database)
    } catch (error) {
      console.log("Database Read:", error.message)
    }
  }

  public query(condition: Function){
    try{
      const content = this.getAll()
      const result = content.data.filter(condition)

      if(result.length === 0){
        return false
      }

      if(result.length === 1){
        return result[0]
      }

      return result
    } catch (error){
      console.log("Database search:", error.message)
    }
  }

  public update(id: string | Number, newValue: any){
    try{
      const content = this.getAll()
      const index = content.data.findIndex((item: any) => item.id === id)

      if(index === -1){
        throw new Error(`Update: Element with id: ${id} not found`)
      }

      content.data[index] = {...content.data[index], ...newValue}

      writeFileSync(this._databaseLocal, JSON.stringify(content, null, 2))
    }catch(error){
      console.log("Database update", error.message)
    }
  }

  public delete(id: string | Number){
    try{
      const content = this.getAll()
      const index = content.data.findIndex((item: any) => item.id === id)

      if(index === -1){
        throw new Error(`Delete: Element with id: ${id} not found`)
      }

      content.data.splice(index, 1)

      writeFileSync(this._databaseLocal, JSON.stringify(content, null, 2))
    }catch(error){
      console.log("Database delete", error.message)
    }
  }
}
