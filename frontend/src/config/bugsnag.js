import React from 'react';
import bugsnag from '@bugsnag/js';
import bugsnagReact from '@bugsnag/plugin-react';

const bugsnagClient = bugsnag({
  apiKey: '35fe91b2a87bc4822c516a9bc592a339',
  notifyReleaseStages: ['production', 'beta'],
  releaseStage: process.env.ENVIRONMENT
});
bugsnagClient.use(bugsnagReact, React);
export const ErrorBoundary = bugsnagClient.getPlugin('react');
