import { client, apiError } from "./_client"
import { formatYMD } from "@/Types/func/index"

export async function getOddGroups(handicap) {
  try {
    const response = await client().get(`odd-groups/${handicap}`)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}

export async function getReport(homeOdd, awayOdd, handicap) {
  try {
    const response = await client().get(`report/${homeOdd}/${awayOdd}/${handicap}`)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}

export async function getReport2(handicap) {
  try {
    const param = handicap ? `/${handicap}` : ''
    const response = await client().get(`report2${param}`)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}

export async function getHandicapGroups() {
  try {
    const response = await client().get(`handicap-groups`)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}

export async function getCorrectScoreMatch(start_date = new Date(), end_date = new Date(), exclude7m = false) {
  try {
    const response = await client().get(`correctscore-match/${formatYMD(start_date)}/${formatYMD(end_date)}/${exclude7m == true ? 'exclude':''}`)
    return response.data
  } catch (error) {
    console.error(error)
    throw apiError(error, {})
  }
}

export async function getUnmatched(start_date = undefined, end_date = undefined) {
    try {
      if(start_date && end_date){
        const response = await client().get(`unmatched/${formatYMD(start_date)}/${formatYMD(end_date)}`)
        return response.data
      }else{
        const response = await client().get(`unmatched`)
        return response.data
      }
    } catch (error) {
      console.error(error)
      throw apiError(error, {})
    }
  }

export async function manualUpdate7mid(gameId, game7mId) {
  try {
    const response = await client().get(`manual-update-7mid/${gameId}/${game7mId}`)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}

export async function fetch7mGames(start_date = new Date(), end_date = new Date()){
  try {
    const response = await client().get(`games/${formatYMD(start_date)}/${formatYMD(end_date)}`)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}

export async function setMatchingGame(body) {
  try {
    const response = await client().post(`set-matching`,body)
    return response.data
  } catch (error) {
    throw apiError(error, {})
  }
}


export async function getDashboard(start_date = null, end_date = null) {
  try {
    if(start_date == null && end_date == null){
      const response = await client().get(`dashboard`)
      return response.data
    }

    const response = await client().get(`dashboard/${formatYMD(start_date)}/${formatYMD(end_date)}/${exclude7m == true ? 'exclude':''}`)
    return response.data

  } catch (error) {
    console.error(error)
    throw apiError(error, {})
  }
}