import React from 'react';

import Translate from './Translate';
import Pagination from './Pagination';
import Notification from './Notification';
import SortLink from './SortLink';
import OrderListSearch from './OrderListSearch';

const OrderList = props => {
  return (
    <div className="card">
      <div className="card-body">
        <div className="table-responsive">
          <Notification {...props} />
          <OrderListSearch {...props} />
          <table className="table table-striped table-bordered">
            <thead className="text-center">
              <tr className="lh-15">
                <th>
                  <SortLink sortKey="id" title="ID" />
                </th>
                <th>
                  <SortLink sortKey="uuid" title="Order #" />
                </th>
                <th>
                  <SortLink sortKey="user_id" title="Order creator" />
                </th>
                <th>
                  <SortLink sortKey="client_id" title="Client" />
                </th>
                <th>
                  <SortLink sortKey="product_id" title="Product" />
                </th>
                <th className="w-min-200">
                  <Translate>Recent status updated by</Translate>
                </th>
                <th>
                  <SortLink sortKey="status_id" title="Status" />
                </th>
                <th>
                  <Translate>Actions</Translate>
                </th>
              </tr>
            </thead>
            <tbody>{props.renderOrderTableRows()}</tbody>
          </table>
          <Pagination {...props.orders.pagination} />
        </div>
      </div>
    </div>
  );
};

export default OrderList;
