// Fetch data by url
export const fetchGetData = async (url) => {
  try {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}${url}`)
    const data = await response.json()
    return data
  } catch (error) {
    console.log(error.message)
    return null
  }
}

export const fetchPostData = async (url, requestBody) => {
  try {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}${url}`, {
      method: "POST",
      headers: {
        "Content-type": "application/json",
        "Accept": "application/json"
      },
      body: JSON.stringify(requestBody)
    })
    const data = await response.json()
    return data
  } catch (error) {
    console.log(error.message)
    return null
  }
}


export const fetchPostDataWithToken = async (url, requestBody, token) => {
  try {
    const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}${url}`, {
      method: "POST",
      headers: {
        "Content-type": "application/json",
        "Authorization": "Bearer " + token
      },
      body: JSON.stringify(requestBody)
    })
    const data = await response.json()
    return data
  } catch (error) {
    console.log(error.message)
    return null
  }
}

// Check if the day is a weekend (Bangladesh)
export const isWeekend = (date) => {
  return date.day() === 5 || date.day() === 6
}

// Check if the day is friday
export const isFriday = (date) => {
  return date.day() === 5
}

// Check if the day is saturday
export const isSaturday = (date) => {
  return date.day() === 6
}

export const isEmpty = (obj) => {
  return obj ? Object.keys(obj).length === 0 : true
}
