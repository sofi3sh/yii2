import React from 'react';
import { shallow } from 'enzyme';
import { PureOrderForm } from './OrderForm';

it('renders without crashing', () => {
  shallow(<PureOrderForm getWizzardSteps={() => {}} translations={() => {}} />);
});
