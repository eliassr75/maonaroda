const api = {
    internal: "/api/v1/endpoint/",
    external: "/api/aplication/endpoint/"
}

const configDataTable = {
    stateSave: true,
    responsive: true,
    language: {
        searchPlaceholder: 'Faça uma pesquisa',
        zeroRecords: "Não encontramos resultados...",
        sSearch: '',
        sLengthMenu: '_MENU_',
        sLength: 'dataTables_length',
        info: 'Mostrando página _PAGE_ de _PAGES_ | Total de Registros: _TOTAL_',
        infoFiltered: '(Filtrado de _MAX_ resultados)',
        infoEmpty: "Total de Registros: _TOTAL_",
        oPaginate: {
            sFirst: '<i class="bi bi-arrow-left-circle"></i>',
            sPrevious: '<i class="bi bi-arrow-left-circle"></i>',
            sNext: '<i class="bi bi-arrow-right-circle"></i>',
            sLast: '<i class="bi bi-arrow-right-circle"></i>'
        }
    }
}


async function getEstados() {
    const response = await fetch('https://brasilapi.com.br/api/ibge/uf/v1');
    const estados = await response.json();
    return estados;
}

// Função para obter a lista de cidades de um estado
async function getCidades(estadoSigla) {
    const response = await fetch(`https://brasilapi.com.br/api/ibge/municipios/v1/${estadoSigla}`);
    const cidades = await response.json();
    return cidades;
}

// Função para obter estados e suas cidades
async function getEstadosECidades() {
    const estados = await getEstados();
    const estadosECidades = {};

    for (const estado of estados) {
        const cidades = await getCidades(estado.sigla);
        estadosECidades[estado.nome] = cidades.map(cidade => cidade.nome);
    }

    return estadosECidades;
}

function createpassword (type, ele) {
    document.getElementById(type).type = document.getElementById(type).type == "password" ? "text" : "password"
    let icon = ele.childNodes[0].classList
    let stringIcon = icon.toString()
    if (stringIcon.includes("ri-eye-line")) {
        ele.childNodes[0].classList.remove("ri-eye-line")
        ele.childNodes[0].classList.add("ri-eye-off-line")
    }
    else {
        ele.childNodes[0].classList.add("ri-eye-line")
        ele.childNodes[0].classList.remove("ri-eye-off-line")
    }
}

async function verificaLogin() {
    try {
        const logged = await $.ajax({
            method: "GET",
            url: api.external,
            data: { query: "check-logged", hash: sessionStorage.getItem('logged') },
            dataType: "JSON"
        });
        return logged.logged;
    } catch (error) {
        console.error("Erro ao verificar se o usuário está logado:", error);
        return false;
    }
}

function spinner(el){

    $(el).html(`
    
    <div class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    
    `)
    
}

function _alert(msg, type, _spinner=false){

    $(".custom-alert").html(`
    
    <div class="alert alert-${type} rounded-pill alert-dismissible fade show">
        <div class="d-flex">${_spinner ? `<div id="spinner" class="me-2"></div>` : ""} ${msg}</div>
        <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="bi bi-x"></i>
        </button>
    </div>
    
    `).addClass("my-3").show(300)
    
    if (_spinner){
        spinner("#spinner")
    }
    
}

function auto_remove_alert(timeout=5000){
    setTimeout(() => {
        $(".custom-alert").html(``).hide(300)
    }, timeout)
}

function checkPassword(){

    const password = $("#password").val()
    const new_password = $("#confirm-password").val()

    if (password == new_password) {

        if(password.length >= 8 && new_password.length >= 8) {
            $('.btn-submit').attr("disabled", false)
            auto_remove_alert(0)
        }else{
            _alert("A deve ter no mínimo 8 caracteres!", "danger")
        }

    } else {
        _alert("As senhas não coincidem!", "danger")
        $('.btn-submit').attr("disabled", true)
    }
}

function body_modal(ref, params){

    $(".modal-content").html(`
    
    <div class="text-center">
        <div class="spinner-border m-5" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="p-2 text-center">
        <button type="button" class="btn btn-light label-btn" data-bs-dismiss="modal">
            <i class="bi bi-ban label-btn-icon"></i> Cancelar
        </button>
    </div>
    
    `)
    
    verificaLogin().then(function(logged) {
        
        let method = ""
        let url_api = ""

        const allowed_mods = ["password-reset", "new-login"]
        
        if (!logged && !allowed_mods.includes(ref)){
            ref = ""
        }
        
        switch(ref){
        
            case 'password-reset':
                
                method = "POST"
                url_api = api.internal
                
                $(".modal-content").html(`
        
                    <form id="password-reset" name="password-reset">
                        <input type="hidden" name="query" value="password-reset"/>
                        <div class="modal-header">
                            <h6 class="modal-title">
                                <i class="bi bi-arrow-repeat"></i> Recuperação de Senha
                            </h6>
                            <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="authentication authentication-basic">
                                <div class="custom-alert"></div>
                                <div class="card-body">
                                    <div class="input-box mb-3" data-bs-validate="Valid email is required: ex@abc.xyz">
                                        <input type="email" class="form-control form-control-lg" id="username" name="username" placeholder="email@email.com" required>
                                        <span class="authentication-input-icon"><i class="ri-mail-fill text-default fs-15 op-7"></i></span>
                                    </div>

                                    <div class="col-xl-12 d-grid mb-3">
                                        <button type="submit" class="btn btn-lg btn-primary btn-submit">Recuperar</button>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:void(0);" class="btn btn btn-primary-light" onclick="body_modal('login', {})">
                                        <i class="bi bi-arrow-left-circle"></i> Voltar
                                        </a>
                                    </div>
                                </div>
        
                            </div>  
                        </div>
                    </form>
                    
                `)
                break;
            case 'new-login':
                
                method = "POST"
                url_api = api.internal
                
                $(".modal-content").html(`
        
                    <form id="new-login" name="new-login">
                    
                        <input type="hidden" name="query" value="new-login"/>
                        <div class="modal-header">
                            <h6 class="modal-title">
                                <i class="bi bi-box-arrow-in-right"></i> Sign Up
                            </h6>
                            <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="authentication authentication-basic">
                                <div class="custom-alert"></div>
                                <div class="card-body">
                                    <div class="input-box mb-3" data-bs-validate="Valid name is required: Lucas Souza">
                                        <input type="text" class="form-control form-control-lg" id="name" name="name" minlength="10" placeholder="Lucas Sousa" required>
                                        <span class="authentication-input-icon"><i class="ri-user-fill text-default fs-15 op-7"></i></span>
                                    </div>
                                    <div class="input-box mb-3" data-bs-validate="Valid email is required: ex@abc.xyz">
                                        <input type="email" class="form-control form-control-lg" id="username" name="username" placeholder="email@email.com" required>
                                        <span class="authentication-input-icon"><i class="ri-mail-fill text-default fs-15 op-7"></i></span>
                                    </div>
                                    <div class="input-group input-box mb-3">
                                        <input type="password" class="form-control form-control-lg" onkeyup="checkPassword()" id="password" name="password" maxlength="8" placeholder="password" required>
                                        <span class="authentication-input-icon"><i class="ri-lock-2-fill text-default fs-15 op-7"></i></span>
                                        <button type="button" aria-label="button" class="btn btn-light" onclick="createpassword('password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <div class="input-group input-box mb-3">
                                        <input type="password" class="form-control form-control-lg" onkeyup="checkPassword()" id="confirm-password" name="confirm-password" maxlength="8" autocomplete="new-password" placeholder="Confirme a senha" required>
                                        <span class="authentication-input-icon"><i class="ri-lock-2-fill text-default fs-15 op-7"></i></span>
                                        <button type="button" aria-label="button" class="btn btn-light" onclick="createpassword('confirm-password',this)" id="button-addon3"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>

                                    <div class="col-xl-12 d-grid mb-3">
                                        <button type="submit" class="btn btn-lg btn-primary btn-submit">Criar Conta</button>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <a href="javascript:void(0);" class="btn btn-lg btm-w-lg btn-primary-light " onclick="body_modal('login', {})">
                                        <i class="bi bi-arrow-left-circle"></i> Voltar
                                        </a>
                                    </div>
                                </div>
        
                            </div>  
                        </div>
                    </form>
                    
                `)
                
                break;
            case 'campaign':
            
                method = "POST"
                url_api = api.internal

                getEstadosECidades().then(estadosECidades => {
                    // Iterar sobre o objeto estadosECidades para acessar chaves e valores
                    for (const [estado, cidades] of Object.entries(estadosECidades)) {
                        console.log(`Estado: ${estado}`);
                        console.log(`Cidades: ${cidades.join(', ')}`);
                    }

                    $(".modal-content").html(`
        
                    <form id="new-campaign" name="new-campaign">
                        <input type="hidden" name="query" value="${params.form}"/>
                        ${params.form == "edit-campaign" ? `<input type="hidden" name="campaign_id" value="${params.id}"/>` : ""}
                        <div class="modal-header">
                            <h6 class="modal-title">
                                <i class="bi bi-wallet2"></i> ${params.form == "edit-campaign" ? "Editar Campanha":"Nova Campanha"}
                            </h6>
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="custom-alert"></div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="input-campaing-name" name="campaing-name" placeholder="Nome da Campanha" required>
                                <label for="input-campaing-name">Nome da Campanha</label>
                            </div>
                            <hr>
                            <div class="form-check my-2">
                                <input class="form-check-input" type="checkbox" name="input-campaign-mod" id="input-campaign-mod">
                                <label class="form-check-label" for="input-campaign-mod">
                                    Especificar Valor (R$)
                                </label>
                            </div>
                            
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <div class="form-floating">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control br-money" id="input-campaign-value" name="input-campaign-value" placeholder="Valor da Campanha" readonly value="0">
                                            <label for="input-campaign-value">Valor total da Collab</label>
                                        </div>
                                    </div>
                                </div>
                            
                                <span class="fs-12 text-danger" id="aviso_total"></span>
                            </div>
                            <div class="form-group my-2">
                                <label for="wallets_categoria">Categorias da Collab:</label>
                                <select class="select2 form-control" name="wallets_categoria" id="wallets_categoria" multiple required>
                                    
                                </select>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input" type="radio" name="wallets_type" id="collab-input-type-public" value="0" required>
                                <label class="form-check-label" for="collab-input-type-public">
                                    Pública 
                                </label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input" type="radio" name="wallets_type" id="collab-input-type-private" value="1" required checked>
                                <label class="form-check-label" for="collab-input-type-private">
                                    Privada
                                </label>
                            </div>
                            <span class="fs-12 text-warning" id="aviso_type">
                                <i class="bi bi-incognito"></i> Apenas convidados tem acesso.
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary-light label-btn btn-submit">
                                <i class="bi bi-floppy label-btn-icon"></i> ${params.form == "edit-collab" ? "Salvar":"Adicionar"} 
                            </button> 
                            <button type="button" class="btn btn-light label-btn" data-bs-dismiss="modal">
                                <i class="bi bi-ban label-btn-icon"></i> Fechar
                            </button>
                        </div>
                    </form>
                    
                `)

                    $(`input[type="checkbox"][name="input-campaign-mod"]`).change(function () {

                        if(this.checked){
                            $("#input-campaign-value").prop("required", true).prop("readonly", false).val("")
                            $("#aviso_total").html(`Nesta modalidade, é obrigatório o valor total da collab.`)
                        }else{
                            $("#input-campaign-value").prop("required", false).prop("readonly", true).val("0")
                            $("#aviso_total").html(``)
                        }
                    })

                    if (params.form == "edit-collab"){
                        $("#collab-input-name").val(params.name).change()
                        $(`input[type="radio"][name="wallets_mod"][value="${params.value_total > 0 ? '1' : '0'}"]`).click();
                        $(`input[type="radio"][name="wallets_type"][value="${params.type == 1 ? '1' : '0'}"]`).click();
                    }


                }).catch(error => {
                    console.error('Erro ao obter dados:', error);
                });


                
                break;
            case 'edit-campaign':
                    
                    $.ajax({
                        type: "GET",
                        url: api.internal,
                        data: {
                            query: ref,
                            id: params.id
                        },
                        dataType: "json",
                        success: function (data) {
                            // console.log(data)
                            if (data.success){
                                body_modal('collab', data)
                            }
                        },
                        error: function(data){
                            console.log(data)
                        }
                    })
                    
                break;
            case 'logout':
            
                method = "GET"
                url_api = api.external
                
                $(".modal-content").html(`
                
                <form id="new-collab" name="new-collab">
                    <input type="hidden" name="query" value="end-session"/>
                    <div class="h-100">
                        <div class="alert custom-alert1 alert-primary  h-100" >
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                            <div class="text-center px-5 pb-0">
                                <svg class="custom-alert-icon svg-primary" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                                <h5>Encerrar sessão?</h5>
                                <p class="">Ao encerrar a sessão, você não poderá executar ações no sistema</p>
                                <div class="">
                                    <button type="button" class="btn btn-sm btn-outline-danger m-1" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-sm btn-primary btn-submit m-1">Continuar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                `)
                
                break;
            case 'user':
            
                method = "POST"
                url_api = api.internal
                // email, username, password, city, state, country, postcode, gender, phone, ddi_phone, country_phone, document
                $(".modal-content").html(`
        
                <form id="new-user" name="new-user">
                    <input type="hidden" name="query" value="${params.form}"/>
                    ${params.form == "edit-user" ? `<input type="hidden" name="user_id" value="${params.id}"/>` : ""}
                    <div class="modal-header">
                        <h6 class="modal-title">
                            <i class="bi bi-person-add"></i> ${params.form == "edit-user" ? "Editar Usuário":"Novo Usuário"}
                        </h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="custom-alert"></div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="user-input-name" name="user_name" placeholder="Nome do Usuário" required>
                            <label for="user-input-name"><i class="bi bi-person"></i> Nome do Usuário</label>
                        </div>
                        <hr>
                        <div class="form-check my-2">
                            <input class="form-check-input" type="radio" name="user_gender" id="user-input-gender-1" value="0" required checked>
                            <label class="form-check-label" for="user-input-gender-1">
                                Acumulativa
                            </label>
                        </div>
                        <div class="form-check my-2">
                            <input class="form-check-input" type="radio" name="user_gender" id="user-input-gender-2" value="1" required>
                            <label class="form-check-label" for="user-input-gender-2">
                                Progressiva
                            </label>
                        </div>
                        
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control br-money" id="collab-input-total-value" name="wallets_value_total" placeholder="Valor total da Collab" readonly value="0">
                                        <label for="collab-input-total-value">Valor total da Collab</label>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="fs-12 text-danger" id="aviso_total"></span>
                        </div>
                        <div class="form-group my-2">
                            <label for="wallets_categoria">Categorias da Collab:</label>
                            <select class="select2-multiple-max-5 form-control" name="wallets_categoria[]" id="wallets_categoria" multiple required>
                                ${values_select}
                            </select>
                        </div>
                        <div class="form-check my-2">
                            <input class="form-check-input" type="radio" name="wallets_type" id="collab-input-type-public" value="0" required>
                            <label class="form-check-label" for="collab-input-type-public">
                                Pública 
                            </label>
                        </div>
                        <div class="form-check my-2">
                            <input class="form-check-input" type="radio" name="wallets_type" id="collab-input-type-private" value="1" required checked>
                            <label class="form-check-label" for="collab-input-type-private">
                                Privada
                            </label>
                        </div>
                        <span class="fs-12 text-warning" id="aviso_type">
                            <i class="bi bi-incognito"></i> Apenas convidados tem acesso.
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary-light label-btn btn-submit">
                            <i class="bi bi-floppy label-btn-icon"></i> ${params.form == "edit-collab" ? "Salvar":"Adicionar"} 
                        </button> 
                        <button type="button" class="btn btn-light label-btn" data-bs-dismiss="modal">
                            <i class="bi bi-ban label-btn-icon"></i> Fechar
                        </button>
                    </div>
                </form>
                    
                `)
                break;
            default:
            
                method = "GET"
                url_api = api.internal
                
                $(".modal-content").html(`
        
                    <form id="login" name="login">
                        <input type="hidden" name="query" value="login">
                        <div class="modal-header">
                            <h6 class="modal-title">
                                <i class="bi bi-box-arrow-in-right"></i> Sign In
                            </h6>
                            <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="authentication authentication-basic">
                                <div class="custom-alert"></div>
                                <div class="card-body">
                                    <div class="input-box mb-3" data-bs-validate="Valid email is required: ex@abc.xyz">
                                        <input type="text" class="form-control form-control-lg" id="signin-username" name="username" placeholder="email@email.com" required>
                                        <span class="authentication-input-icon"><i class="ri-mail-fill text-default fs-15 op-7"></i></span>
                                    </div>
                                    <div class="input-group input-box mb-3">
                                        <input type="password" class="form-control form-control-lg" id="signin-password" name="password" placeholder="password" required>
                                        <span class="authentication-input-icon"><i class="ri-lock-2-fill text-default fs-15 op-7"></i></span>
                                        <button type="button" aria-label="button" class="btn btn-light" onclick="createpassword('signin-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <div class="col-xl-12 d-grid mb-3">
                                        <button type="submit" class="btn btn-lg btn-primary btn-submit">Entrar</button>
                                    </div>
                                    <hr>
                                    <div class="text-center mb-2"><a href="javascript:void(0);" class="text-danger" onclick="body_modal('password-reset', {})">Não lembra a senha?</a></div>
                                    <div class="text-center mb-0">Não é um membro?<a href="javascript:void(0);" class="text-primary ms-2" onclick="body_modal('new-login', {})">Criar uma conta</a></div>
                                </div>
        
                            </div>  
                        </div>
                    </form>
                    
                `)
                
                if (ref != "login"){
                    _alert("Você precisa estar logado para utilizar este recurso.", "danger")
                }
                break;
        
        }
        
        $('.br-money').mask("000.000.000,00", {reverse: true})
        $(".select2-multiple-max-5").select2({
            dropdownParent: $('#modal'),
            maximumSelectionLength: 5,
            placeholder: "Escolha até 5 tipos de Collab",
        });
        
        $(`form`).on("submit", function(e){
    
            e.preventDefault();
                        
            _alert("Um instante...", "warning", true)
            $('.btn-submit').prop('disabled', true);

            if (method == "POST"){
                var formdata = new FormData($(`form`)[0]);
            }else{
                var formdata = $(`form`).serialize();
            }
        
            $.ajax({
                type: method,
                url: url_api,
                data: formdata,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                
                    // console.log(data)
                    
                    if (data.end_session){
                        window.location.href = "/"
                    }
    
                    if (data.success) {
                        _alert(data.msg ? data.msg : data.success, "primary")
                        
                        
                        if (data.logged){
                            sessionStorage.setItem("logged", data.logged)
                            
                            if (data.persist_login){
                                localStorage.setItem("persist_login", data.persist_login)
                            }

                        }
                        
                        setTimeout(() => {
                            window.location.reload()
                        }, 1500)
                        

                    } else {
                        _alert(data.msg, "warning")
                        auto_remove_alert()
                    }
                    
                    
                    if (data.error) {
                        _alert(data.error, "primary")
                    }
                    $('.btn-submit').prop('disabled', false);
        
                },
                error: function(e) {
                    console.log(e);
                    _alert(e.responseJSON ? e.responseJSON.error : e.statusText, "danger")
                    $('.btn-submit').prop('disabled', false);
                }
            })
            
        });

        
    }).catch(function(error) {
        console.error("Erro ao verificar o status de login:", error);
    });
    
    
}
    
$(document).ready(() => {

    $(".select2").select2({
        placeholder: "Cidades participantes",
    });

})