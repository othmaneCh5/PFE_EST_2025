/**
 * app-ecommerce-order-list Script
 */
'use strict';

$(function () {
  //-------------------------------------------------------
  // 1) Setup style variables (dark/light theme)
  //-------------------------------------------------------
  let borderColor, bodyBg, headingColor;
  if (typeof isDarkStyle !== 'undefined' && isDarkStyle) {
    borderColor = config.colors_dark.borderColor;
    bodyBg = config.colors_dark.bodyBg;
    headingColor = config.colors_dark.headingColor;
  } else {
    borderColor = config.colors.borderColor;
    bodyBg = config.colors.bodyBg;
    headingColor = config.colors.headingColor;
  }

  //-------------------------------------------------------
  // 2) Define status/payment style objects
  //-------------------------------------------------------
  const statusObj = {
    'initiée':   { title: 'Initiée', class: 'bg-label-warning' },
    'en cours':  { title: 'En cours', class: 'bg-label-info' },
    'terminée':  { title: 'Terminée', class: 'bg-label-success' }
  };

  const paymentObj = {
    1: { title: 'Payé', class: 'text-success' },
    2: { title: 'En cours', class: 'text-warning' },
    3: { title: 'Echoué', class: 'text-danger' },
    4: { title: 'Annulé', class: 'text-secondary' }
  };

  //-------------------------------------------------------
  // 3) Initialize the DataTable
  //-------------------------------------------------------
  const dt_order_table = $('.datatables-order');
  let dt_products;

  if (dt_order_table.length) {
    dt_products = dt_order_table.DataTable({
      ajax: '/app/ecommerce/commande/data',
      columns: [
        { data: 'id' },
        { data: 'id' },
        { data: 'id' },
        { data: 'created_at' },
        { data: 'client_name' },
        { data: 'paiement' },
        { data: 'status' },
        { data: 'methode' },
        { data: null }
      ],
      columnDefs: [
        // 0) Responsive control
        {
          targets: 0,
          className: 'control',
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          render: () => ''
        },
        // 1) Checkboxes
        {
          targets: 1,
          orderable: false,
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          },
          render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">',
          searchable: false
        },
        // 2) Order ID
        {
          targets: 2,
          render: (data, type, full) => `<a href="#"><span>#${full.id}</span></a>`
        },
        // 3) Created_at => date + time
        {
          targets: 3,
          render: function (data) {
            const dateObj = new Date(data);
            const formatted = dateObj.toLocaleString('fr-FR', {
              day: '2-digit',
              month: 'short',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit',
              second: '2-digit'
            });
            return `<span class="text-nowrap">${formatted}</span>`;
          }
        },
        // 4) Client column
        {
          targets: 4,
          render: function (data, type, full) {
            const name = full.client_name || '—';
            const email = full.client_email || '—';
            const avatarImg = full.avatar || null;

            let avatarHtml = '';
            if (avatarImg) {
              avatarHtml = `<img src="${assetsPath}img/avatars/${avatarImg}" alt="Avatar" class="rounded-circle" width="32" height="32">`;
            } else {
              const initials = name.match(/\b\w/g)?.join('').substring(0, 2).toUpperCase() || '??';
              const bgColors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info'];
              const color = bgColors[Math.floor(Math.random() * bgColors.length)];
              avatarHtml = `
                <span class="avatar-initial rounded-circle bg-label-${color} text-white d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                  ${initials}
                </span>`;
            }
            return `
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">${avatarHtml}</div>
                <div class="d-flex flex-column">
                  <h6 class="m-0 text-heading">${name}</h6>
                  <small>${email}</small>
                </div>
              </div>`;
          }
        },
        // 5) Paiement
        {
          targets: 5,
          render: (data) => {
            let badgeClass = 'text-warning';
            if (data.toLowerCase() === 'payé') badgeClass = 'text-success';
            else if (data.toLowerCase() === 'échoué') badgeClass = 'text-danger';
            else if (data.toLowerCase() === 'annulé') badgeClass = 'text-secondary';
            return `<span class="${badgeClass} text-capitalize">${data}</span>`;
          }
        },
        // 6) Status
        {
          targets: 6,
          render: function (data) {
            const status = data?.toLowerCase();
            const statusConfig = statusObj[status] || { title: status, class: 'bg-label-secondary' };
            return `<span class="badge ${statusConfig.class} text-capitalize">${statusConfig.title}</span>`;
          }
        },
        // 7) Méthode
        {
          targets: 7,
          render: (data, type, full) => {
            return `
              <div class="d-flex align-items-center text-nowrap">
                <img src="${assetsPath}img/icons/payments/${full.methode}.png" alt="${full.methode}" width="29">
                <span class="ms-2">${full.methode}</span>
              </div>
            `;
          }
        },
        // 8) Actions
        {
          targets: 8,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: (data, type, full) => {
            return `
              <div class="d-flex justify-content-sm-start align-items-sm-center">
                <button class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="ti ti-dots-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end m-0">
                  <a href="/app/ecommerce/commande/details/${full.id}" class="dropdown-item">Details</a>
                  <a href="javascript:void(0);" class="dropdown-item view-record" data-id="${full.id}">View</a>
                  <a href="javascript:void(0);" class="dropdown-item delete-record" data-id="${full.id}">
                    Delete
                  </a>
                </div>
              </div>`;
          }
        }
      ],
      order: [[2, 'asc']],
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
      // Export Buttons
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ti ti-upload ti-xs me-2"></i>Export',
          buttons: [
            // ... same code for print/csv/excel/pdf/copy
          ]
        },
        // ADD ORDER => offcanvas
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 mb-1 ti-xs"></i><span class="d-none d-sm-inline-block">Ajouter commande</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasEcommerceOrderAdd'
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              const data = row.data();
              return 'Details of ' + (data.client_name || 'Order');
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            const data = $.map(columns, function (col) {
              return col.title
                ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                     <td>${col.title}:</td><td>${col.data}</td>
                   </tr>`
                : '';
            }).join('');
            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });

    // Minor styling adjustments
    $('.dataTables_length').addClass('ms-n2');
    $('.dt-action-buttons').addClass('pt-0');
    $('.dataTables_filter').addClass('ms-n3 mb-0 mb-md-6');
  }

  //-------------------------------------------------------
  // 4) 'View' -> fetch data, fill form, show offcanvas
  //-------------------------------------------------------
  $(document).on('click', '.view-record', function () {
    const id = $(this).data('id');
    $.get(`/app/ecommerce/commande/${id}/edit`, function (res) {
      // fill fields
      $('#eCommerceOrderAddForm').attr('data-id', res.id);
      $('#ecommerce-order-add-client').val(res.id_client);
      $(`input[name="paiement"][value="${res.paiement}"]`).prop('checked', true);
      $(`input[name="status"][value="${res.status}"]`).prop('checked', true);
      $(`input[name="methode"][value="${res.methode}"]`).prop('checked', true);

      $('#orderSubmitBtn').text('Enregistrer');
      $('#offcanvasEcommerceOrderAdd').offcanvas('show');
    }).fail(function (xhr) {
      alert('Failed to load order data.');
      console.error(xhr.responseText);
    });
  });

  //-------------------------------------------------------
  // 5) Create vs. Update
  //-------------------------------------------------------
  const offcanvasEl = $('#offcanvasEcommerceOrderAdd');
  const formEl      = $('#eCommerceOrderAddForm');
  const submitBtn   = $('#orderSubmitBtn');

  submitBtn.on('click', function () {
    const existingId = formEl.attr('data-id') || null;
    const isUpdate = !!existingId;

    const payload = {
      _token: $('meta[name="csrf-token"]').attr('content'),
      client_id: $('#ecommerce-order-add-client').val(),
      paiement:  $('input[name="paiement"]:checked').val(),
      status:    $('input[name="status"]:checked').val(),
      methode:   $('input[name="methode"]:checked').val()
    };

    let url = '/app/ecommerce/commande';
    if (isUpdate) {
      url = `/app/ecommerce/commande/${existingId}`;
      payload._method = 'PUT';
    }

    $.ajax({
      url: url,
      method: 'POST',
      data: payload,
      success: function () {
        offcanvasEl.offcanvas('hide');
        formEl[0].reset();
        formEl.removeAttr('data-id');
        submitBtn.text('Ajouter');
        dt_products.ajax.reload();
      },
      error: function (xhr) {
        alert('Error saving order.');
        console.error(xhr.responseText);
      }
    });
  });

  //-------------------------------------------------------
  // 6) Reset form when offcanvas closes
  //-------------------------------------------------------
  offcanvasEl.on('hidden.bs.offcanvas', function () {
    formEl[0].reset();
    formEl.removeAttr('data-id');
    submitBtn.text('Ajouter');
  });

  //-------------------------------------------------------
  // 7) Delete with SweetAlert + real DB removal
  //-------------------------------------------------------
  $(document).on('click', '.delete-record', function (e) {
    e.preventDefault();

    console.log('Delete clicked!');

    const row = $(this).closest('tr');
    const orderId = $(this).data('id');

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-2 waves-effect waves-light',
        cancelButton: 'btn btn-label-secondary waves-effect waves-light'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: `/app/ecommerce/commande/${orderId}`,
          method: 'POST',
          data: {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function () {
            dt_products.row(row).remove().draw();

            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Order has been removed from the database.',
              customClass: {
                confirmButton: 'btn btn-success waves-effect waves-light'
              }
            });
          },
          error: function (xhr) {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Failed to delete order. Check console for details.',
              customClass: {
                confirmButton: 'btn btn-danger waves-effect waves-light'
              }
            });
            console.error(xhr.responseText);
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'Order was not deleted!',
          icon: 'info',
          customClass: {
            confirmButton: 'btn btn-success waves-effect waves-light'
          }
        });
      }
    });
  });

  //-------------------------------------------------------
  // 8) Final styling adjustments
  //-------------------------------------------------------
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
