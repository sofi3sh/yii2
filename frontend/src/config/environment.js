export const NODE_ENV = process.env.ENVIRONMENT;

export const ENVIRONMENT_CONFIG = Object.freeze({
  development: {
    API_BASE_URL: 'http://localhost:8000'
  },
  beta: {
    API_BASE_URL: 'http://134.209.240.149'
  },
  production: {
    API_BASE_URL: 'http://cs.standartpark.com'
  }
});

export default NODE_ENV
  ? ENVIRONMENT_CONFIG[NODE_ENV]
  : ENVIRONMENT_CONFIG.development;
