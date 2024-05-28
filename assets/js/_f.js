const api = {
    internal: "http://localhost:61520/api/v1/endpoint/",
    external: "http://localhost:61520/api/aplication/endpoint/"
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

const types_collabs = [

    {
    title: "Pessoal",
    desc: "Para despesas pessoais do dia a dia, como contas, transporte, lazer, alimentação, entre outros."
    },
    {
    title: "Familiar",
    desc: "Para despesas compartilhadas por membros da família, como moradia, alimentação, educação, cuidados médicos, entre outros."
    },
    {
    title: "Viagem",
    desc: "Para economizar dinheiro para viagens, incluindo passagens, hospedagem, alimentação, transporte local e atividades turísticas."
    },
    {
    title: "Eventos Especiais",
    desc: "Para economizar para eventos especiais, como casamentos, festas de aniversário, formaturas, férias, entre outros."
    },
    {
    title: "Objetivos Financeiros",
    desc: "Para atingir objetivos financeiros específicos, como comprar um carro, fazer uma reforma em casa, pagar uma dívida, investir em educação, entre outros."
    },
    {
    title: "Emergências",
    desc: "Para situações de emergência ou imprevistos, como despesas médicas inesperadas, reparos de emergência, perda de emprego, entre outros."
    },
    {
    title: "Educação",
    desc: "Para despesas relacionadas à educação, como mensalidades escolares, materiais didáticos, cursos extracurriculares, entre outros."
    },
    {
    title: "Saúde e Bem-Estar",
    desc: "Para despesas relacionadas à saúde e bem-estar, como consultas médicas, medicamentos, seguros de saúde, academia, entre outros."
    },
    {
    title: "Doações e Caridade",
    desc: "Para contribuições financeiras a causas sociais, instituições de caridade, projetos comunitários, entre outros."
    },
    {
    title: "Investimentos",
    desc: "Para investimentos financeiros, como ações, fundos mútuos, imóveis, previdência privada, entre outros."
    },
    {
    title: "Entretenimento",
    desc: "Para despesas relacionadas a entretenimento, como cinema, teatro, shows, eventos esportivos, assinaturas de streaming, entre outros."
    },
    {
    title: "Moda e Beleza",
    desc: "Para despesas relacionadas a roupas, acessórios, produtos de beleza, salão de beleza, cosméticos, entre outros."
    },
    {
    title: "Tecnologia",
    desc: "Para despesas relacionadas a eletrônicos, gadgets, dispositivos móveis, assinaturas de serviços digitais, entre outros."
    },
    {
    title: "Automóveis",
    desc: "Para despesas relacionadas a veículos, como combustível, seguro auto, manutenção, estacionamento, multas, entre outros."
    },
    {
    title: "Hobbies e Passatempos",
    desc: "Para despesas relacionadas a hobbies e passatempos, como livros, música, artesanato, esportes, colecionáveis, entre outros."
    },
    {
    title: "Pets",
    desc: "Para despesas relacionadas a animais de estimação, como alimentação, cuidados veterinários, produtos de higiene, brinquedos, entre outros."
    },
    {
    title: "Presentes e Celebrações",
    desc: "Para despesas relacionadas a presentes para amigos e familiares, festas de aniversário, celebrações de feriados, entre outros."
    },
    {
    title: "Home Office",
    desc: "Para despesas relacionadas a um escritório em casa, como mobília, equipamentos de escritório, suprimentos de papelaria, entre outros."
    },
    {
    title: "Sustentabilidade",
    desc: "Para despesas relacionadas a práticas sustentáveis, como reciclagem, energia renovável, produtos eco-friendly, entre outros."
    },
    {
    title: "Aposentadoria",
    desc: "Para economizar dinheiro para aposentadoria, incluindo planos de previdência privada, investimentos de longo prazo, entre outros."
    },
    {
    title: "Aluguel e Moradia",
    desc: "Para despesas relacionadas a aluguel ou financiamento de moradia, incluindo aluguel, hipoteca, condomínio, contas de serviços públicos, entre outros."
    },
    {
    title: "Alimentação e Supermercado",
    desc: "Para despesas relacionadas a alimentos, compras de supermercado, refeições fora de casa, delivery, entre outros."
    },
    {
    title: "Impostos e Taxas",
    desc: "Para economizar dinheiro para pagar impostos, taxas, contribuições, multas, entre outros encargos fiscais."
    },
    {
    title: "Viagens a Negócios",
    desc: "Para despesas relacionadas a viagens de negócios, como hospedagem, transporte, alimentação, despesas de representação, entre outros."
    },
    {
    title: "Treinamento e Desenvolvimento Profissional",
    desc: "Para despesas relacionadas a treinamentos, cursos, workshops, livros, certificações, eventos profissionais, entre outros."
    },
    {
    title: "Segurança e Proteção",
    desc: "Para despesas relacionadas a segurança pessoal, proteção de bens, seguros de vida, seguro residencial, entre outros."
    },
    {
    title: "Aventuras e Experiências",
    desc: "Para despesas relacionadas a atividades ao ar livre, viagens de aventura, ingressos para parques temáticos, entre outros."
    },
    {
    title: "Investimentos Sociais",
    desc: "Para investimentos em projetos sociais, impacto social, responsabilidade social corporativa, entre outros."
    },
    {
    title: "Arte e Cultura",
    desc: "Para despesas relacionadas a arte, cultura, museus, galerias de arte, eventos culturais, entre outros."
    },
    {
    title: "Desenvolvimento Comunitário",
    desc: "Para despesas relacionadas a projetos comunitários, doações locais, apoio a iniciativas sociais, entre outros."
    }

]

types_collabs.sort((a, b) => {
    const titleA = a.title.toUpperCase();
    const titleB = b.title.toUpperCase();

    if (titleA < titleB) {
        return -1;
    }
    if (titleA > titleB) {
        return 1;
    }
    return 0;
});

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
    
    `).addClass("my-3")
    
    if (_spinner){
        spinner("#spinner")
    }
    
}

function auto_remove_alert(){
    setTimeout(() => {
        $(".custom-alert").html(``).hide(300)
    }, 5000)
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
        
        if (!logged || !allowed_mods.includes(ref)){
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
                                        <input type="email" class="form-control form-control-lg" id="name" name="name" placeholder="Lucas Sousa" required>
                                        <span class="authentication-input-icon"><i class="ri-user-fill text-default fs-15 op-7"></i></span>
                                    </div>
                                    <div class="input-box mb-3" data-bs-validate="Valid email is required: ex@abc.xyz">
                                        <input type="email" class="form-control form-control-lg" id="username" name="username" placeholder="email@email.com" required>
                                        <span class="authentication-input-icon"><i class="ri-mail-fill text-default fs-15 op-7"></i></span>
                                    </div>
                                    <div class="input-group input-box mb-3">
                                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="password" required>
                                        <span class="authentication-input-icon"><i class="ri-lock-2-fill text-default fs-15 op-7"></i></span>
                                        <button type="button" aria-label="button" class="btn btn-light" onclick="createpassword('password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>
                                    <div class="input-group input-box mb-3">
                                        <input type="password" class="form-control form-control-lg" id="confirm-password" name="confirm-password" placeholder="Confirme a senha" required>
                                        <span class="authentication-input-icon"><i class="ri-lock-2-fill text-default fs-15 op-7"></i></span>
                                        <button type="button" aria-label="button" class="btn btn-light" onclick="createpassword('confirm-password',this)" id="button-addon3"><i class="ri-eye-off-line align-middle"></i></button>
                                    </div>

                                    <div class="col-xl-12 d-grid mb-3">
                                        <button type="submit" class="btn btn-lg btn-primary btn-submit">Criar Conta</button>
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
            case 'collab':
            
                method = "POST"
                url_api = api.internal
                let values_select = ""
                
                if (params.form == "edit-collab"){
                    types_collabs.forEach((e, i) => {
                        values_select += `<option value="${e.title}" ${params.category.includes(e.title) ? "selected" : ""}>${e.title}</option>`
                    })
                }else{
                    types_collabs.forEach((e, i) => {
                        values_select += `<option value="${e.title}">${e.title}</option>`
                    })
                }
                
                $(".modal-content").html(`
        
                    <form id="new-collab" name="new-collab">
                        <input type="hidden" name="query" value="${params.form}"/>
                        ${params.form == "edit-collab" ? `<input type="hidden" name="wallets_id" value="${params.id}"/>` : ""}
                        <div class="modal-header">
                            <h6 class="modal-title">
                                <i class="bi bi-wallet2"></i> ${params.form == "edit-collab" ? "Editar Collab":"Nova Collab"}
                            </h6>
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="custom-alert"></div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="collab-input-name" name="wallets_name" placeholder="Nome da Collab" required>
                                <label for="collab-input-name">Nome da Collab</label>
                            </div>
                            <hr>
                            <div class="form-check my-2">
                                <input class="form-check-input" type="radio" name="wallets_mod" id="collab-input-mod-1" value="0" required checked>
                                <label class="form-check-label" for="collab-input-mod-1">
                                    Acumulativa
                                </label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input" type="radio" name="wallets_mod" id="collab-input-mod-2" value="1" required>
                                <label class="form-check-label" for="collab-input-mod-2">
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
                
                $(`input[type="radio"][name="wallets_mod"]`).change(function () {
                
                    if(this.value == "1"){
                        $("#collab-input-total-value").prop("required", true).prop("readonly", false).val("")
                        $("#aviso_total").html(`Nesta modalidade, é obrigatório o valor total da collab.`)
                    }else{
                        $("#collab-input-total-value").prop("required", false).prop("readonly", true).val("0")
                        $("#aviso_total").html(``)
                    }
                })
                
                $(`input[type="radio"][name="wallets_type"]`).change(function () {
                
                    if(this.value == "1"){
                        $("#aviso_type").html(`<i class="bi bi-incognito"></i> Apenas convidados tem acesso.`)
                    }else{
                        $("#aviso_type").html(`<i class="bi bi-people"></i> Todos os usuários podem participar da collab`)
                    }
                })
                
                if (params.form == "edit-collab"){
                    $("#collab-input-name").val(params.name).change()
                    $(`input[type="radio"][name="wallets_mod"][value="${params.value_total > 0 ? '1' : '0'}"]`).click();
                    $(`input[type="radio"][name="wallets_type"][value="${params.type == 1 ? '1' : '0'}"]`).click();
                }
                
                break;
            case 'edit-collab':
                    
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
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="always-logged" name="always-logged">
                                            <label class="form-check-label text-muted fw-normal" for="always-logged">
                                                Manter logado?
                                            </label>
                                        </div>
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

    // const mp = new MercadoPago('TEST-c684c203-f1b9-479e-9aee-5f0fb641c0b5', { locale: 'pt-BR' });
    
    // mp.bricks().create("wallet", "wallet_container", {
    //     initialization: {
    //         preferenceId: "wallet_containers",
    //     },
    // });

    $(".select2").select2({
        placeholder: "Cidades participantes",
    });

})