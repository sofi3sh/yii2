import React, { Component } from 'react';

import OrderList from '../components/OrderList';
import withData from '../hocs/withData';
import OrderListTableRow from '../components/OrderListTableRow';

class OrderListContainer extends Component {
  state = {
    openedMenuOrderId: null
  };

  getTranslation({ titleSourceMessage, titleSourceMessage: { translations } }) {
    return (
      (translations.length && translations[0].translation) ||
      titleSourceMessage.message
    );
  }

  handleMenuToggle = isOpen => this.setState({ openedMenuOrderId: isOpen });

  mapOptionsToDropdownProps({ data, dropdownValue, optionLabel }) {
    return Object.values(data).map(option => {
      let label = '';
      if (!option.titleSourceMessage) {
        label = option[optionLabel];
      } else {
        const { translations } = option.titleSourceMessage;
        label =
          (translations.length && translations[0].translation) ||
          option.titleSourceMessage.message;
      }
      return {
        value: option[dropdownValue],
        label
      };
    });
  }

  renderOrderTableRows = () => {
    const {
      orders: { orders }
    } = this.props;
    if (!orders) {
      return false;
    }
    return Object.values(orders).map(order => {
      return (
        <OrderListTableRow
          {...this.props}
          key={order.id}
          getTranslation={this.getTranslation}
          order={order}
          openedMenuOrderId={this.state.openedMenuOrderId}
          handleMenuToggle={this.handleMenuToggle}
          mapOptionsToDropdownProps={this.mapOptionsToDropdownProps}
        />
      );
    });
  };

  render() {
    return (
      <OrderList
        {...this.props}
        renderOrderTableRows={this.renderOrderTableRows}
        mapOptionsToDropdownProps={this.mapOptionsToDropdownProps}
      />
    );
  }
}

const getUrlParams = () => new URLSearchParams(window.location.search);

export default withData(
  [
    'orders',
    'translations',
    'statuses',
    'products',
    'user',
    'printed-forms',
    'users',
    'clients'
  ],
  {
    orders: {
      params: getUrlParams,
      controller: 'order-list'
    },
    translations: {
      controller: 'order-list'
    },
    statuses: {
      controller: 'order-status'
    },
    products: {
      controller: 'order-list'
    },
    user: {
      controller: 'order-list'
    },
    'printed-forms': {
      controller: 'order-list'
    },
    users: {
      controller: 'order-list'
    },
    clients: {
      controller: 'order-list'
    }
  }
)(OrderListContainer);
