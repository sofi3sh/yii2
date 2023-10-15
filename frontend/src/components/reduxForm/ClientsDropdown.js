import React from 'react';

import Dropdown from './Dropdown';
import withData from '../../hocs/withData';
import { required } from '../validation/validationRules';

const mapClientsToDropdownProps = clients => {
  return clients.map(client => ({
    value: client.id,
    label: client.full_name
  }));
};

const ClientsDropdown = ({ clients }) => (
  <Dropdown
    label="Client"
    name="client_id"
    options={mapClientsToDropdownProps(clients)}
    defaultEmpty={true}
    validate={[required]}
  />
);

ClientsDropdown.defaultProps = {
  clients: []
};

export default withData(['clients'])(ClientsDropdown);
