import React, { Component } from 'react';
import { Link } from 'react-router-dom';

import Input from './form/Input';
import Dropdown from './form/Dropdown';
import Translate from './Translate'

class OrderListSearch extends Component {
  state = {
    isOpen: false,
    search: {}
  };

  toggleSearch = () => this.setState(({ isOpen }) => ({ isOpen: !isOpen }));

  getSearchParams = () =>
    Object.keys(this.state.search)
      .map(key => key + '=' + this.state.search[key])
      .join('&');

  renderSearchParams = () => `?search=true&${this.getSearchParams()}`;

  onInputChange = ({ target: { value, name } }) => {
    let newValue = { [name]: value };
    if (value) {
      return this.setState(({ search }) => ({
        search: { ...search, ...newValue }
      }));
    }

    let newSearchState = this.state.search;
    delete newSearchState[name];
    return this.setState({ search: newSearchState });
  };

  render() {
    const {
      mapOptionsToDropdownProps,
      users,
      clients,
      products,
      statuses
    } = this.props;
    return (
      <div className="mb-3">
        <div className="row">
          <div className="col-md-10 text-right">
            {this.state.isOpen && (
              <React.Fragment>
                <Link
                  to={this.renderSearchParams()}
                  className="btn btn-primary mr-3"
                >
                  <Translate>Search</Translate>
                </Link>
                <Link to="/" className="btn btn-danger">
                  <Translate>Discard</Translate>
                </Link>
              </React.Fragment>
            )}
          </div>
          <div className="col-md-2 text-right">
            <div>
              <button
                className="btn btn-primary mb-2"
                onClick={this.toggleSearch}
              >
                <i className="fa fa-cog"></i>
              </button>
            </div>
          </div>
        </div>
        {this.state.isOpen && (
          <React.Fragment>
            <div className="row mb-3">
              <div className="col-md-6">
                <Input label={'ID'} name="id" onChange={this.onInputChange} />
              </div>

              <div className="col-md-6">
                <Input
                  label={'Order #'}
                  name="uuid"
                  onChange={this.onInputChange}
                />
              </div>
            </div>
            <div className="row">
              <div className="col-md-6">
                <Dropdown
                  label={'Order creator'}
                  name="user_id"
                  onChange={this.onInputChange}
                  options={mapOptionsToDropdownProps({
                    data: users,
                    dropdownValue: 'id',
                    optionLabel: 'full_name'
                  })}
                />
              </div>
              <div className="col-md-6">
                <Dropdown
                  label={'Client'}
                  name="client_id"
                  onChange={this.onInputChange}
                  options={mapOptionsToDropdownProps({
                    data: clients,
                    dropdownValue: 'id',
                    optionLabel: 'full_name'
                  })}
                />
              </div>
            </div>
            <div className="row">
              <div className="col-md-6">
                <Dropdown
                  label={'Product'}
                  name="product_id"
                  onChange={this.onInputChange}
                  options={mapOptionsToDropdownProps({
                    data: products,
                    dropdownValue: 'id'
                  })}
                />
              </div>
              <div className="col-md-6">
                <Dropdown
                  label={'Status'}
                  name="status_id"
                  onChange={this.onInputChange}
                  options={mapOptionsToDropdownProps({
                    data: statuses,
                    dropdownValue: 'id'
                  })}
                />
              </div>
            </div>
          </React.Fragment>
        )}
      </div>
    );
  }
}

export default OrderListSearch;
