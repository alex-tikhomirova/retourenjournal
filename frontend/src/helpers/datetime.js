const dateSql = (str) => {

    const date = str?new Date(str):new Date()
    return [
        date.getFullYear(),
        String(date.getMonth()+1).padStart(2, "0"),
        String(date.getDate()).padStart(2, "0")
    ].join('-')
}

const timeSql = (str) => {

    const date = str?new Date(str):new Date()
    return date.getHours() + ':' + String(date.getMinutes()).padStart(2, "0") + ':' + String(date.getSeconds()).padStart(2, "0");
}

const dateTimeSql = (str) => {
    return dateSql(str) + ' ' + timeSql(str);
}

const dateStr = (str) => {
    const date = str?new Date(str):new Date()
    return [
        String(date.getDate()).padStart(2, "0"),
        String(date.getMonth()+1).padStart(2, "0"),
        String(date.getFullYear()).slice(-2)
    ].join('.')
}

const timeStr = (str, seconds = true) => {
    const date = str?new Date(str):new Date()
    const time = [
        String(date.getHours()).padStart(2, "0"),
        String(date.getMinutes()).padStart(2, "0"),
    ]
    if (seconds){
        time.push(String(date.getSeconds()).padStart(2, "0"))
    }
    return time.join(':')
}

const dateTimeStr = (str, seconds = true) => {
    return dateStr(str) + ' ' + timeStr(str, seconds)
}

export {dateSql, dateTimeSql, timeSql, dateStr, timeStr, dateTimeStr}