$(document).ready(function () {
    $("#patientTreatmentDiagnostic").select2({
        multiple: true
    });

    //Mostrar campo de otro diagnóstico para captura
    $('#patientTreatmentDiagnostic').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.id == 9) {
            $("#divPatientTreatmentDiagnosticOther").show();
            $("#patientTreatmentDiagnosticOther").val("");
        }
    });
    //Ocultar campo de otro diagnóstico
    $('#patientTreatmentDiagnostic').on('select2:unselect', function (e) {
        var data = e.params.data;
        if (data.id == 9) {
            $("#divPatientTreatmentDiagnosticOther").hide();
            $("#patientTreatmentDiagnosticOther").val("");
        }
    });

    //CLASIFICACIÓN
    let patient_category_id = $("#patientCategoryId").val();
    let category_treatment_id = $("#categoryTreatmentId").val();

    let category_treatment_status = $("#categoryTreatmentStatusId").val();
    $("#divPatientTreatmentDiagnosticOther").hide(); //Mostrar captura de otro diagnóstico

    if (patient_category_id == 3) {
        //Es un tratamiento.
        $("#divPatientTreatment").show(); //Mostrar tipos de tratamiento
        $("#divPatientTreatmentDiagnostic").show(); //Mostrar diagnósticos de tratamiento
        $("#patientCategorySave").hide(); //Guardar nueva categoría
        $("#patientTreatmentDiagnosticOther").prop("disabled", true);
        $("input[name='treatmentLocation']").prop("disabled", true);

        //DIAGNÓSTICOS SELECCIONADOS PARA EL TRATAMIENTO
        $.ajax({
            url: "./?action=treatmentdiagnostics/getByPatientCategory",
            type: "POST",
            data: {
                categoryTreatmentId: category_treatment_id
            },
            success: function (data) {
                $("#divPatientTreatmentDiagnosticOther").hide();
                $("#patientTreatmentDiagnosticOther").prop("disabled", true);
                let selectedDiagnostics = [];
                $.each(JSON.parse(data), function (index, diagnostic) {
                    selectedDiagnostics.push(diagnostic["treatment_diagnostic_id"]);
                    if (diagnostic["treatment_diagnostic_id"] == 9) {//Si incluye otro diagnóstico, mostrar campo de captura
                        $("#divPatientTreatmentDiagnosticOther").show();
                        $("#patientTreatmentDiagnosticOther").val(diagnostic["description"]);
                    }
                });
                $("#patientTreatmentDiagnostic").val(selectedDiagnostics).trigger("change");
            },
            error: function () {
            }
        });

        $("#patientTreatmentDiagnostic").prop("disabled", true);

        if (category_treatment_status == 2) {
            $("#divPregnancyOptions").show(); //Mostrar las secciones de pruebas y embarazo
            $("#divPregnancyTestDate").show();//Mostrar selección de fecha de prueba de embarazo para notificación
            $("#divTreatmentOptions").hide()
            $("#divPregnancyResultOptions").hide();
        } else {
            $("#divPregnancyOptions").hide(); //Ocultar las secciones de pruebas y embarazo
            $("#divPregnancyTestDate").hide();//ocultar selección de fecha de prueba de embarazo para notificación
            $("#divTreatmentOptions").show(); //Mostrar opciones de cancelar y finalizar
        }

    } else {
        //Es otra categoría
        $("#divTreatmentOptions").hide(); //Mostrar opciones de cancelar y finalizar
        $("#divPatientTreatment").hide(); //Mostrar tipos de tratamiento
        $("#divPatientTreatmentDiagnostic").hide(); //Mostrar diagnósticos de tratamiento
        $("#divPatientTreatmentLocation").hide(); //Mostrar ubicación de tratamiento(local-externo)
        $("#patientCategorySave").hide(); //Guardar nueva categoría
        $("#divPregnancyOptions").hide();
        $("#divPregnancyTestDate").hide();//ocultar selección de fecha de prueba de embarazo para notificación
    }

    //DATOS DEL EMBARAZO
    $("#btnSaveExternalPregnancy").hide(); //Activar hasta que se seleccione 
    $("#btnSavePregnancyTestDate").hide(); //Activar hasta que se cambie la fecha de la prueba del embarazo

    if ($("#patientPregnancyId").val()) {
        $("#divPregnancyDetail").show();
        $("#divExternalPregnancyDetail").hide();
    } else {
        $("#divPregnancyDetail").hide();
        $("#divExternalPregnancyDetail").show();
        $("#lblPregnancyDetail").text("NO EMBARAZADA");
    }
});

//CATEGORÍA PACIENTE

function savePatientCategory() {
    if ($("#patientBirthday").val() != '' && $("#patientBirthday").val() != "0000-00-00") {
        $.ajax({
            url: "./?action=patientcategories/add-patient-category",
            type: "POST",
            data: {
                patient_category_id: $("#patientCategory").val(),
                patient_treatment_id: $("#patientTreatment").val(),
                patient_treatment_diagnostics: $("#patientTreatmentDiagnostic").val(),
                patient_treatment_diagnostic_other: $("#patientTreatmentDiagnosticOther").val(),
                patient_id: $("#patientId").val(),
                treatment_location_id: $("input[name='treatmentLocation']:checked").val(),
            },
            success: function (data) {
                let categoryData = JSON.parse(data);
                $("#categoryTreatmentId").val(categoryData["id"]);
                $("#isTreatmentPregnancyTest").val(categoryData["is_pregnancy_test"]);
                if ($("#patientCategory").val() == 3) {
                    $("#divTreatmentOptions").show(); //Mostrar opciones de cancelar y finalizar
                    $("#patientCategorySave").hide();
                    $("#patientCategory").prop("disabled", true);
                    $("#patientTreatment").prop("disabled", true);
                    $("#patientTreatmentDiagnostic").prop("disabled", true);
                    $("#patientTreatmentDiagnosticOther").prop("disabled", true);
                    $("input[name='treatmentLocation']").prop("disabled",true);
                    //Si se registró a la paciente como donadora de óvulos se muestra el mensaje de que se generó su id
                    if ($("#patientTreatment").val() == 12) {
                        Swal.fire(
                            'El paciente se ha registrado como donante.',
                            'Puedes encontrarlo en la sección de Donantes y darle seguimiento.',
                            'success'
                        )
                    }
                } else {
                    $("#patientCategorySave").hide();
                    $("#patientCategory").prop("disabled", false);
                    $("#patientTreatment").prop("disabled", false);
                    $("#patientTreatmentDiagnostic").prop("disabled", false);
                    $("#patientTreatmentDiagnosticOther").prop("disabled", false);
                    $("input[name='treatmentLocation']").prop("disabled",false);
                }
            },
            error: function () {
                Swal.fire(
                    'Error',
                    'Ha ocurrido un error al registrar la categoría, recarga la página.',
                    'error'
                )
            }
        });
    } else {
        Swal.fire(
            'Registra la fecha de nacimiento del paciente.',
            'Es necesaria para dar de alta el tratamiento, ya que afecta a las estadísticas en los reportes.',
            'warning'
        );
    }
}

function selectCategory() {
    if ($("#patientCategory").val() == 0) {
        $("#patientCategorySave").hide();
        $("#divPatientTreatment").hide();
        $("#divPatientTreatmentDiagnostic").hide();
        $("#patientTreatmentDiagnosticOther").hide();
        $("#divPatientTreatmentLocation").hide();
    } else if ($("#patientCategory").val() == 3) {
        //Mostrar los tratamientos para la categoría de tratamiento de fertilidad
        $("#divPatientTreatment").show();
        $("#divPatientTreatmentDiagnostic").show();
        $("#patientTreatmentDiagnosticOther").show();
        $("#divPatientTreatmentLocation").show();
        $("#patientCategorySave").show();
        $("#btnSaveCategory").text("Guardar Tratamiento");
    } else {
        $("#divPatientTreatment").hide();
        $("#divPatientTreatmentDiagnostic").hide();
        $("#patientTreatmentDiagnosticOther").hide();
        $("#divPatientTreatmentLocation").hide();
        $("#patientCategorySave").show();
        $("#btnSaveCategory").text("Guardar Categoría");
    }
}

function cancelTreatment() {
    Swal.fire({
        title: '¿Deseas cancelar el tratamiento?',
        text: "Esta acción no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cancelar tratamiento',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            Swal.fire({
                title: 'Motivo de Cancelación',
                input: 'text',
                inputValue: $(this).text().trim(),
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: (value) => {
                    $.ajax({
                        type: "POST",
                        url: "./?action=patientcategories/updatePatientCategoryStatus",
                        data: {
                            category_treatment_id: $("#categoryTreatmentId").val(),
                            treatment_status_id: 3,
                            note: value,
                        },
                        error: function () {
                            Swal.fire(
                                'Error',
                                'No se pudo cancelar el tratamiento..',
                                'error'
                            )
                        },
                        success: function (data) {
                            $("#patientCategory").prop("disabled", false);
                            $("#patientTreatment").prop("disabled", false);
                            $("#patientTreatmentDiagnostic").prop("disabled", false);
                            $("#patientTreatmentDiagnosticOther").prop("disabled", false);
                            $("#patientTreatmentDiagnosticOther").val("");
                            $("#divPatientTreatment").hide();
                            $("#divPatientTreatmentDiagnostic").hide();
                            $("#divPatientTreatmentLocation").hide();
                            $("input[name='treatmentLocation']").prop("disabled", false);
                            $("input[name='treatmentLocation'][value='1']").prop("checked",true);
                            $("#divTreatmentOptions").hide();
                            $("#categoryTreatmentId").val(0);
                            $("#patientCategory").val(0); //Select
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        }
    })
}

function finishTreatment() {
    //Cambiar el estatus del tratamiento
    //Si el tratamiento requiere prueba de embarazo marcar el estatus 2 (Pendiente prueba y mostrar datos de prueba de embarazo
    if ($("#isTreatmentPregnancyTest").val() == 1) {
        Swal.fire({
            title: '¿Deseas finalizar el tratamiento?',
            text: "Después de esto, el sistema le notificará para realizar la prueba de embarazo si aplica.",
            icon: 'warning',
            width: '60rem',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            denyButtonColor: '#D5A423',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Finalizar, SÍ realizar prueba de embarazo',
            denyButtonText: 'Finalizar, NO realizar prueba de embarazo',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.value == true) {
                //Finalizar tratamiento con prueba de embarazo
                $.ajax({
                    type: "POST",
                    url: "./?action=patientcategories/updatePatientCategoryStatus",
                    data: {
                        category_treatment_id: $("#categoryTreatmentId").val(),
                        treatment_status_id: 2,
                        isEmbryoTransfer: 1
                    },
                    error: function () {
                        Swal.fire(
                            'Error',
                            'No se pudo finalizar el tratamiento..',
                            'error'
                        )
                    },
                    success: function (data) {
                        $("#divTreatmentOptions").hide();
                        $("#divPregnancyOptions").show();
                        $("#divPregnancyTestDate").show();
                        $("#divPregnancyResultOptions").hide();
                    }
                });
            } else if (result.value == false) {
                //Finalizar tratamiento pero sin prueba de embarazo
                $.ajax({
                    type: "POST",
                    url: "./?action=patientcategories/updatePatientCategoryStatus",
                    data: {
                        category_treatment_id: $("#categoryTreatmentId").val(),
                        treatment_status_id: 4,
                        isEmbryoTransfer: 0
                    },
                    error: function () {
                        Swal.fire(
                            'Error',
                            'No se pudo finalizar el tratamiento..',
                            'error'
                        )
                    },
                    success: function (data) {

                        $("#patientCategory").prop("disabled", false);
                        $("#patientTreatment").prop("disabled", false);
                        $("#patientTreatmentDiagnostic").prop("disabled", false);
                        $("#patientTreatmentDiagnosticOther").prop("disabled", false);
                        $("#patientTreatmentDiagnosticOther").val("");
                        $("#divPatientTreatment").hide();
                        $("#divPatientTreatmentDiagnostic").hide();
                        $("#divPatientTreatmentLocation").hide();
                        $("input[name='treatmentLocation']").prop("disabled", false);
                        $("input[name='treatmentLocation'][value='1']").prop("checked",true);
                        $("#divTreatmentOptions").hide();
                        $("#divPregnancyOptions").hide();
                        $("#divPregnancyTestDate").hide();
                        $("#categoryTreatmentId").val(0);
                        $("#patientCategory").val(0);

                    }
                });
            }
        });
    }
    else {
        //Si el tratamiento NO requiere prueba de embarazo marcar el estatus 4 (Finalizado)
        Swal.fire({
            title: '¿Deseas finalizar el tratamiento?',
            text: "Esta acción no se podrá revertir.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, finalizar el tratamiento',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: "POST",
                    url: "./?action=patientcategories/updatePatientCategoryStatus",
                    data: {
                        category_treatment_id: $("#categoryTreatmentId").val(),
                        treatment_status_id: 4,
                        isEmbryoTransfer: 0
                    },
                    error: function () {
                        Swal.fire(
                            'Error',
                            'No se pudo finalizar el tratamiento..',
                            'error'
                        )
                    },
                    success: function (data) {

                        $("#patientCategory").prop("disabled", false);
                        $("#patientTreatment").prop("disabled", false);
                        $("#patientTreatmentDiagnostic").prop("disabled", false);
                        $("#patientTreatmentDiagnosticOther").prop("disabled", false);
                        $("#patientTreatmentDiagnosticOther").val("");
                        $("#divPatientTreatment").hide();
                        $("#divPatientTreatmentDiagnostic").hide();
                        $("#divPatientTreatmentLocation").hide();
                        $("input[name='treatmentLocation']").prop("disabled", false);
                        $("input[name='treatmentLocation'][value='1']").prop("checked",true);
                        $("#divTreatmentOptions").hide();
                        $("#divPregnancyOptions").hide();
                        $("#divPregnancyTestDate").hide();
                        $("#categoryTreatmentId").val(0);
                        $("#patientCategory").val(0);

                    }
                });
            }
        });
    }
}

//EMBARAZOS - RESULTADO DE TRATAMIENTO

function showPregnancyResult() {
    //Mostrar las opciones de embarazo
    $("#divPregnancyResultOptions").show();
    $("#btnPregnancyTest").hide();
}

function hidePregnancyResult() {
    //Mostrar las opciones de embarazo
    $("#divPregnancyResultOptions").hide();
    $("#btnPregnancyTest").show();
}

function savePregnancyResult() {
    //Cambiar el estatus y marcar como en pruebas de embarazo
    Swal.fire({
        title: '¿Deseas guardar los resultados de la prueba de embarazo?',
        text: "Esta acción no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: "POST",
                url: "./?action=patientcategories/updatePatientTreatmentResult",
                data: {
                    category_treatment_id: $("#categoryTreatmentId").val(),
                    pregnancy_test_result: $('input[type=radio][name=pregnancy_result]:checked').val()
                },
                error: function () {
                    Swal.fire(
                        'Error',
                        'No se pudo guardar el resultado del tratamiento...',
                        'error'
                    )
                },
                success: function (data) {
                    if (data != "success") {
                        //Si el embarazo fue exitoso mostrar el mensaje en la parte superior
                        let pregnancy_details = JSON.parse(data);

                        $("#lblPregnancyDetail").text("EMBARAZO POR TRATAMIENTO " + $('#patientTreatment option:selected').text() + " REGISTRADO EL " + pregnancy_details[1]);
                        $("#divPregnancyDetail").show();
                        $("#patientPregnancyId").val(pregnancy_details[0]);
                        $("#divExternalPregnancyDetail").hide();
                    }
                    //Ocultar información de tratamientos
                    $("#patientCategory").prop("disabled", false);
                    $("#patientTreatment").prop("disabled", false);
                    $("#patientTreatmentDiagnostic").prop("disabled", false);
                    $("#divPatientTreatment").hide();
                    $("#divPatientTreatmentDiagnostic").hide();
                    $("#divPatientTreatmentLocation").hide();
                    $("input[name='treatmentLocation']").prop("disabled", false);
                    $("input[name='treatmentLocation'][value='1']").prop("checked", true);
                    $("#divTreatmentOptions").hide();
                    $("#divPregnancyOptions").hide();
                    $("#divPregnancyTestDate").hide();
                    $("#categoryTreatmentId").val(0);
                    $("#patientCategory").val(0);
                }
            });
        }
    })
}

function selectPregnancyTestDate() {
    //Mostrar las opciones de embarazo
    $("#btnSavePregnancyTestDate").show();
}

function savePregnancyTestDate() {
    //Guardar la fecha para notificar de la prueba de embarazo
    $.ajax({
        type: "POST",
        url: "./?action=patientcategories/updatePatientPregnancyTestDate",
        data: {
            category_treatment_id: $("#categoryTreatmentId").val(),
            pregnancy_test_date: $("#pregnancyTestDate").val()
        },
        error: function () {
            Swal.fire(
                'Error',
                'No se pudo guardar la fecha de la prueba de embarazo..',
                'error'
            )
        },
        success: function (data) {
            $("#btnSavePregnancyTestDate").hide();
        }
    });
}

function finishPregnancy() {
    Swal.fire({
        title: '¿Deseas marcar como finalizado el embarazo?',
        text: "Esta acción no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, finalizar.',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: "POST",
                url: "./?action=patientpregnancies/finishPatientPregnancy",
                data: {
                    patient_pregnancy_id: $("#patientPregnancyId").val(),
                },
                error: function () {
                    Swal.fire(
                        'Error',
                        'No se pudo finalizar el embarazo..',
                        'error'
                    )
                },
                success: function (data) {
                    $("#divPregnancyDetail").hide();
                    $("#divExternalPregnancyDetail").show();
                    $("#patientPregnancyId").val(0);
                }
            });
        }
    })
}

function showExternalPregnancyOptions() {
    if ($('#externalPregnancy').is(":checked")) {
        $("#btnSaveExternalPregnancy").show();
    } else {
        $("#btnSaveExternalPregnancy").hide();
    }
}

function saveExternalPregnancy() {
    //Guardar Embarazo
    Swal.fire({
        title: '¿Deseas registrar un embarazo externo?',
        text: "Esta acción no se podrá revertir.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, Guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value == true) {
            $.ajax({
                type: "POST",
                url: "./?action=patientpregnancies/addPatientPregnancy",
                data: {
                    patient_id: $("#patientId").val(),
                    category_treatment_id: null,
                    pregnancy_type_id: 2
                },
                error: function () {
                    Swal.fire(
                        'Error',
                        'No se pudo guardar el embarazo externo..',
                        'error'
                    )
                },
                success: function (data) {
                    let pregnancy_details = JSON.parse(data);

                    //Si el embarazo fue exitoso mostrar el mensaje en la parte superior
                    $("#lblPregnancyDetail").text("EMBARAZO EXTERNO REGISTRADO EL " + pregnancy_details[1]);
                    $("#divPregnancyDetail").show();
                    $("#patientPregnancyId").val(pregnancy_details[0]);
                    $("#divExternalPregnancyDetail").hide();
                    $('#externalPregnancy').prop('checked', false);
                    $("#btnSaveExternalPregnancy").hide();
                }
            });
        }
    })
}