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


export async function confirmMatch(gameId, game7mId) {
  try {
    const response = await client().put(`confirmMatch/${gameId}/${game7mId}`)
    return response.data

  } catch (error) {
    console.error(error)
    throw apiError(error, {})
  }
}


export async function fetchReportCountS2() {
  try {
    const response = await client().get(`report/countS2`)
    return response.data

  } catch (error) {
    console.error(error)
    throw apiError(error, {})
  }
}


export async function fetchReportCountLi(s2) {
  try {
    const response = await client().get(`report/countS2/${s2}/li`)
    return response.data

  } catch (error) {
    console.error(error)
    throw apiError(error, {})
  }
}


export class Record{
  data = [];
  page = 1;

  async fetchReportRecords(s2, li) {
    try {
      
      let response = await client().get(`report/records/${s2}/${li}?page=${this.page}`)
      this.data = [...this.data, ...response.data.data]
      if (response.data.current_page < response.data.last_page) {
        this.page++;
        await this.fetchReportRecords(s2, li);
      }else{
        this.data = this.data.map(item => ({
          ...item,
          k: `k-${item.oo}-${item.uo}`
        }));

        this.data = this.groupByKAndHandi(this.data);
      }
    } catch (error) {
      console.error(error)
      throw apiError(error, {})
    }
  }

  groupByKAndHandi(data) {
    return data
      .map(item => ({
        ...item,
        k: `k-${item.oo}-${item.uo}`
      }))
      .reduce((acc, item) => {
        const { k, handi } = item;
        const outcome = item.handi;

        if (!acc[k]) {
          acc[k] = { home: [], away: [] };
        }

        acc[k]['oo'] = item.oo;
        acc[k]['uo'] = item.uo;
        acc[k][outcome].push(item);
        return acc;
      }, {});
  }
}

export async function fetchReportRecords(s2, li) {
  try {
    let page = 1;
    let data = []
    let response = await client().get(`report/records/${s2}/${li}?page=${page}`)
    data = [...data, response.data.data]
    if (response.data.current_page < response.data.last_page) {
      page++;
      response = await client().get(`report/records/${s2}/${li}?page=${page}`)
      data = [...data, response.data.data]
    }
    return data
  } catch (error) {
    console.error(error)
    throw apiError(error, {})
  }
}