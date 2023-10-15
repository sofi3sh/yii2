import axios from 'axios';

import APP_CONFIG from './app';

export default axios.create({
  baseURL: `${APP_CONFIG.API_BASE_URL}/${APP_CONFIG.API_VERSION}`
});
