export const formatDate = function(inputString) {
    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
    const months = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ]

    const date = new Date(inputString)
    const day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
    const month = months[date.getMonth()]
    const year = date.getFullYear()
    const dayOfWeek = daysOfWeek[date.getDay()]

    return `${dayOfWeek} ${day}, ${month}, ${year}`
}
export const formatYMD = function(date) {
  const day = date.getDate()
  const month = date.getMonth() + 1
  const year = date.getFullYear()

  return `${year}-${month}-${day}`
}

export const extractTime = function(inputString) {
    const date = new Date(inputString)
    const hours = date.getHours().toString().padStart(2, "0")
    const minutes = date.getMinutes().toString().padStart(2, "0")
    return `${hours}:${minutes}`
}

export const extractTime7m = function(inputString) {
    const date = new Date(inputString)
    const hours = date.getHours().toString().padStart(2, "0")
    const minutes = date.getMinutes().toString().padStart(2, "0")
    return `${hours}:${minutes}`
}


export const findMatchingIndices = function(
    array1,
    array2
  ) {
    const matchingIndices = []

    for (let i = 0; i < array1.length; i++) {
      const element1 = array1[i]

      for (let j = 0; j < array2.length; j++) {
        const element2 = array2[j]

        if (element1.id === element2.id && element1.gd === element2.gd) {
          matchingIndices.push([i, j])
        }
      }
    }

    return matchingIndices
}

export const getHandicap = (index) => {
  const arr = [
    "=","0+½","½","½+1"
    ,"1","1+1½","1½","1½+2"
    ,"2","2+2½","2½","2½+3"
    ,"3","3+3½","3½","3½+4"
    ,"4","4+4½","4½","4½+5"
    ,"5","5+5½","5½","5½+6"
    ,"6","6+6½","6½","6½+7"
    ,"7","7+7½","7½","7½+8"
    ,"8","8+8½","8½","8½+9"
    ,"9.0"
  ];
  return arr[index];
}

export const getHandicapByValue = (value) => {
  const arr = [
    0, 0.25, 0.5, 0.75,
    1, 1.25, 1.5, 1.75,
    2, 2.25, 2.5, 2.75,
    3, 3.25, 3.5, 3.75,
    4, 4.25, 4.5, 4.75,
    5, 5.25, 5.5, 5.75,
    6, 6.25, 6.5, 6.75,
    7, 7.25, 7.5, 7.75,
    8, 8.25, 8.5, 8.75,
    9
  ];

  const index = arr.findIndex((v) => v == Math.abs(value))

  return index >= 0 ? getHandicap(index) : null;
}

export const getWinLabel = (val) =>{
  const arr = {
    "loss": "Lose",
    "loss_half": "Lose Half",
    "win": "Win",
    "win_half": "Win Half",
    "draw": "Draw",
  }

  return arr[val];
}



export const getOppositeWinLabel = (val) =>{
  const arr = {
    "loss": "Win",
    "loss_half": "Win Half",
    "win": "Lose",
    "win_half": "Lose Half",
    "draw": "Draw",
  }

  return arr[val];
}