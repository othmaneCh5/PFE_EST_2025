/**
 * App eCommerce customer all
 */

'use strict';

$(function () {
  //-------------------------------------------------------
  // 1) DEFINE ALL DOM ELEMENT REFERENCES AT THE TOP
  //-------------------------------------------------------
  const formEl       = $('#eCommerceCustomerAddForm');
  const offcanvasEl  = $('#offcanvasEcommerceCustomerAdd');
  const submitBtn    = $('.data-submit');          // main add/update button
  const nameField    = $('#ecommerce-customer-add-name');
  const emailField   = $('#ecommerce-customer-add-email');
  const contactField = $('#ecommerce-customer-add-contact');
  const addressField = $('#ecommerce-customer-add-address');
  const townField    = $('#ecommerce-customer-add-town');
  const pinField     = $('#ecommerce-customer-add-post-code');
  const buttonEl     = $('#customerSubmitBtn');    // optional: if your Blade uses this ID for the button

  let borderColor, bodyBg, headingColor;
  if (typeof isDarkStyle !== 'undefined' && isDarkStyle) {
    borderColor  = config.colors_dark.borderColor;
    bodyBg       = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor  = (config && config.colors) ? config.colors.borderColor : '#ebebeb';
    bodyBg       = (config && config.colors) ? config.colors.bodyBg : '#fff';
    headingColor = (config && config.colors) ? config.colors.headingColor : '#000';
  }

  //-------------------------------------------------------
  // 2) SETUP DATATABLE
  //-------------------------------------------------------
  const dt_customer_table = $('.datatables-customers');
  let dt_customer;

  if (dt_customer_table.length) {
    dt_customer = dt_customer_table.DataTable({
      ajax: window.CUSTOMERS_DATA_URL, // e.g. "/app/ecommerce/customers/data"
      columns: [
        { data: '' },
        { data: 'id' },
        { data: 'id_client' },
        { data: 'name' },
        { data: 'email' },
        { data: 'tel' },
        { data: 'adresse' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: () => ''
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">',
          checkboxes: { selectAllRender: '<input type="checkbox" class="form-check-input">' }
        },
        {
          targets: 2,
          render: function (data, type, full) {
            return `<span class="text-heading">#${full.id_client}</span>`;
          }
        },
        {
          // Name or Avatar
          targets: 3,
          responsivePriority: 1,
          render: function (data, type, full) {
            const $name = full.name;
            let $output;
            if (full.image) {
              $output = `<img src="${assetsPath}img/avatars/${full.image}" alt="Avatar" class="rounded-circle">`;
            } else {
              const states = ['success','danger','warning','info','dark','primary'];
              const rand = states[Math.floor(Math.random() * states.length)];
              const initials = ($name.match(/\b\w/g) || []).join('').toUpperCase();
              $output = `<span class="avatar-initial rounded-circle bg-label-${rand}">${initials}</span>`;
            }
            return `
              <div class="d-flex align-items-center customer-name">
                <div class="avatar avatar-sm me-3">${$output}</div>
                <div class="d-flex flex-column">
                  <span class="fw-medium">${$name}</span>
                </div>
              </div>
            `;
          }
        },
        {
          targets: 4,
          render: function (data, type, full) {
            return `<span>${full.email}</span>`;
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full) {
            return `
            <div class="d-flex align-items-sm-center">
              <button class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                <i class="ti ti-dots-vertical"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end m-0">
                <a href="javascript:void(0);" class="dropdown-item edit-record" data-id="${full.id}">Edit</a>
                <a href="javascript:void(0);" class="dropdown-item delete-record" data-id="${full.id}">Delete</a>
              </div>
            </div>
            `;
          }
        }
      ],
      order: [[2, 'desc']],
      // Add your DataTable extras: buttons, language, etc.
      dom:
        '<"card-header d-flex flex-wrap flex-md-row flex-column align-items-start align-items-sm-center py-0"' +
        '<"d-flex align-items-center me-5"f>' +
        '<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap mb-6 mb-sm-0"lB>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',

      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search Order',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ti ti-upload ti-xs me-2"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('customer-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('customer-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-export me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('customer-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-text me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('customer-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5, 6],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('customer-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 mb-1 ti-xs"></i><span class="d-none d-sm-inline-block">Add Customer</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceCustomerAdd'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['customer'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });

    // Optional styling adjustments
    $('.dataTables_length').addClass('ms-n2 me-2');
    $('.dt-action-buttons').addClass('pt-0');
    $('.dataTables_filter').addClass('ms-n3 mb-0 mb-md-6');
    $('.dt-buttons').addClass('d-flex flex-wrap');
  }

  //-------------------------------------------------------
  // 3) EDIT => FETCH => FILL FORM => SHOW OFFCANVAS
  //-------------------------------------------------------
  $(document).on('click', '.edit-record', function () {
    const id = $(this).data('id');
    $.get(`/app/ecommerce/customers/${id}/edit`, function (client) {
      // fill fields
      nameField.val(client.name);
      emailField.val(client.email);
      contactField.val(client.tel);
      addressField.val(client.adresse);
      // If you want to parse 'adresse' => do .split(',')

      // Mark form as update mode
      formEl.attr('data-id', client.id);
      // Switch button text => 'Modifier'
      buttonEl.text('Modifier');

      // Show offcanvas
      offcanvasEl.offcanvas('show');
    }).fail(function (xhr) {
      alert('Failed to fetch client data. See console for details.');
      console.error(xhr.responseText);
    });
  });

  //-------------------------------------------------------
  // 4) DELETE => confirm => call route => reload table
  //-------------------------------------------------------
  $(document).on('click', '.delete-record', function () {
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this customer?')) return;

    $.ajax({
      url: `/app/ecommerce/customers/${id}`,
      method: 'POST', // We'll spoof DELETE
      data: {
        _method: 'DELETE',
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function () {
        dt_customer.ajax.reload();
      },
      error: function (xhr) {
        alert('Failed to delete customer.');
        console.error(xhr.responseText);
      }
    });
  });

  //-------------------------------------------------------
  // 5) CREATE / UPDATE => single handler
  //-------------------------------------------------------
  submitBtn.on('click', function () {
    const id = formEl.attr('data-id'); // if present => update
    const isUpdate = !!id;

    const url = isUpdate
      ? `/app/ecommerce/customers/${id}`
      : window.CUSTOMERS_STORE_URL; // e.g. "/app/ecommerce/customers/store"

    // we do POST both times, but spoof PUT if update
    const methodOverride = isUpdate ? 'PUT' : null;

    $.ajax({
      url: url,
      method: 'POST',
      data: {
        _method: methodOverride,
        _token: $('meta[name="csrf-token"]').attr('content'),
        customerName: nameField.val(),
        customerEmail: emailField.val(),
        customerContact: contactField.val(),
        customerAddress1: addressField.val(),
        customerTown: townField.val(),
        pin: pinField.val()
      },
      success: function () {
        offcanvasEl.offcanvas('hide');
        formEl[0].reset();
        formEl.removeAttr('data-id');
        // revert text => "Ajouter"
        buttonEl.text('Ajouter');
        // reload table
        dt_customer.ajax.reload();
      },
      error: function (xhr) {
        alert('Error saving customer.');
        console.error(xhr.responseText);
      }
    });
  });
});

//
// Additional logic for phone mask, form validation, etc.
//
(function () {
  const phoneMaskList = document.querySelectorAll('.phone-mask');
  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
  // Example: formValidation usage, if needed
})
();
