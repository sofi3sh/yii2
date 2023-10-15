import React, { Component } from 'react';
import classNames from 'classnames';
import BasicButton from '../components/BasicButton';
import StatusModal from '../components/StatusModal';
import OrderDeleteModal from '../components/OrderDeleteModal';
import OrderFilesModal from '../components/OrderFilesModal';
import OrderPrintedForms from '../components/OrderPrintedForms';
import OrderUploadModules from '../components/OrderUploadModules';
import Translate from './Translate';
import APP_CONFIG from '../config/app';

const styles = {
  dropdownList: {
    position: 'absolute',
    top: '100%',
    zIndex: 1000,
    float: 'left',
    minWidth: '11.25rem',
    padding: '.5rem 0',
    margin: '.125rem 0 0',
    color: '#333',
    textAlign: 'left',
    listStyle: 'none',
    backgroundColor: '#fff',
    backgroundClip: 'padding-box',
    border: '1px solid rgba(0,0,0,.15)',
    borderRadius: '.1875rem',
    boxShadow: '0 0.25rem 0.5rem rgba(0,0,0,.1)'
  }
};

class OrderListTableRow extends Component {
  state = {
    isDeleted: false,
    isMenuOpened: false,
    currentStatus: this.props.order.status
  };

  handleSuccessDelete = () => {
    this.setState({ isDeleted: true });
  };

  toggleOrderMenu = () => {
    const {
      handleMenuToggle,
      order: { id: orderId }
    } = this.props;
    this.setState(({ isMenuOpened }) => ({
      isMenuOpened: !isMenuOpened
    }));
    handleMenuToggle(orderId);
  };

  handleStatusChange = (newStatus) => {
    this.setState({
      currentStatus: newStatus
    })
  }

  render() {
    const {
      order: { user, client, product, previousStatusLog, allow_fragments },
      order,
      getTranslation,
      openedMenuOrderId
    } = this.props;
    return (
      <tr className={classNames({
         'd-none': this.state.isDeleted ,
         'text-muted': this.state.currentStatus.key == APP_CONFIG.STATUSES.IN_WORK
         })}>
        <td>{order.id}</td>
        <td>{order.uuid}</td>
        <td>{user && user.full_name}</td>
        <td>{client && client.full_name}</td>
        <td>{getTranslation(product)}</td>
        <td>{previousStatusLog && previousStatusLog.user.full_name}</td>
        <td style={{ minHeight: '60px', minWidth: '230px' }}>
          <StatusModal 
            {...this.props} 
            currentOrder={order} 
            onStatusChange={this.handleStatusChange}
          />
        </td>
        <td className="order-actions">
          <div className="list-icons">
            <div className="dropdown">
              <a className="list-icons-item" onClick={this.toggleOrderMenu}>
                <i className="icon-menu9"></i>
              </a>
              {this.state.isMenuOpened && openedMenuOrderId == order.id && (
                <ul className="dropdown-menu-right" style={styles.dropdownList}>
                  {this.state.currentStatus.key !== APP_CONFIG.STATUSES.IN_WORK && (
                  <li>
                    <a
                      href={`/order/update/${order.id}`}
                      className="dropdown-item"
                    >
                      <i className="fa fa-edit"></i>
                      <Translate>Edit</Translate>
                    </a>
                  </li>
                  )}
                  <li>
                    <a
                      href={`/order/view/${order.id}`}
                      className="dropdown-item"
                    >
                      <i className="fa fa-eye"></i>
                      <Translate>View</Translate>
                    </a>
                  </li>
                  <li>
                    <a
                      href={`/order/comments/${order.id}`}
                      className="dropdown-item"
                      target="_blank"
                    >
                      <i className="fa fa-comments"></i>
                      <Translate>View Comments</Translate>
                    </a>
                  </li>
                  <li>
                    <OrderPrintedForms {...this.props} currentOrder={order} />
                  </li>
                  {!!+allow_fragments && (
                    <li>
                      <OrderUploadModules
                        {...this.props}
                        currentOrder={order}
                      />
                    </li>
                  )}
                  <li>
                    <OrderFilesModal {...this.props} currentOrder={order} />
                  </li>
                  {this.state.currentStatus.key !== APP_CONFIG.STATUSES.IN_WORK && (
                  <li>
                    <OrderDeleteModal
                      {...this.props}
                      handleSuccessDelete={this.handleSuccessDelete}
                      currentOrder={order}
                    />
                  </li>
                  )}
                </ul>
              )}
            </div>
          </div>
        </td>
      </tr>
    );
  }
}

export default OrderListTableRow;
