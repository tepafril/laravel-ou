import Axios from "axios"




const client = () => {
    return Axios.create({
        baseURL: 'http://7m.afril.net/api/',
    })
}


function apiError(error, errorMessages) {
  if (!error.response) {
    if (!navigator.onLine) {
      return ApiError.noNetworkConnection()
    }
    return new ApiError(false, "Something went wrong", null, null, null)
  }
  const apiError = ApiError.fromResponse(error.response)
  const message = errorMessages[apiError.serverCode] ?? errorMessages[apiError.httpCode]
  if (message) {
    apiError.message = message
  }
  return apiError
}

class ApiError {
  constructor(
      noNetworkConnection,
     message,
    httpCode,
    serverCode,
    serverMessage
  ) {}

  get default() {
    return this.message
  }

  get key() {
    return `ERROR_${this.serverCode}`
  }

  static noNetworkConnection() {
    return new ApiError(true, "No network connection", null, null, null)
  }

  static fromResponse(response) {
    return new ApiError(
      false,
      response.data.message,
      response.status,
      response.data.error_code,
      response.data.message
    )
  }
}

export { client, apiError }
