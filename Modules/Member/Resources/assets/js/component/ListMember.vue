<template>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <Card 
                :title="stats.money.title" 
                :value="stats.money.value" 
                :percentage="stats.money.percentage" 
                :icon-class="stats.money.iconClass" 
                :icon-background="stats.iconBackground" 
                direction-reverse>
            </Card>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <card
                :title="stats.users.title"
                :value="stats.users.value"
                :percentage="stats.users.percentage"
                :icon-class="stats.users.iconClass"
                :icon-background="stats.iconBackground"
                direction-reverse>
            </card>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <card
                :title="stats.clients.title"
                :value="stats.clients.value"
                :percentage="stats.clients.percentage"
                :icon-class="stats.clients.iconClass"
                :icon-background="stats.iconBackground"
                :percentage-color="stats.clients.percentageColor"
                direction-reverse>
            </card>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0">
            <card
                :title="stats.sales.title"
                :value="stats.sales.value"
                :percentage="stats.sales.percentage"
                :icon-class="stats.sales.iconClass"
                :icon-background="stats.iconBackground"
                direction-reverse>
            </card>
        </div>
    </div>

    <form method="" id="test">
        <div class="import__upload__content">
            <input type="file" name="file" @click="resetFileUpload" accept=".csv">
        </div>

        <div class="mod__modal__btn__unit">
            <button class="mod__btn bg-color-primary" @click="uploadFile" type="button">
                <span>Upload</span>
            </button>
        </div>
    </form>
</template>

<script>
export default {
    name: "ListMember",
    data() {
        return {
            stats: {
                iconBackground: "bg-gradient-success",
                money: {
                    title: "Today's Money",
                    value: "$53,000",
                    percentage: "+55%",
                    iconClass: "ni ni-money-coins",
                },
                users: {
                    title: "Today's Users",
                    value: "2,300",
                    percentage: "+3%",
                    iconClass: "ni ni-world",
                },
                clients: {
                    title: "New Clients",
                    value: "+3,462",
                    percentage: "-2%",
                    iconClass: "ni ni-paper-diploma",
                    percentageColor: "text-danger",
                },
                sales: {
                    title: "Sales",
                    value: "$103,430",
                    percentage: "+5%",
                    iconClass: "ni ni-cart",
                },
            },
            file: null
        }
    },
    methods: {
        async uploadFile() {
            // try {
                const formData = new FormData(document.getElementById('test'));
                // console.log(formData.get('file'));
                // if (this.file !== '') {
                //     formData.append('file', this.file);
                // }
                formData.append('delete_user', this.checkDelete);
                const response = await axios.post(`api/member/upload-file`, formData);
                // if (response !== undefined && response.status === 200) {
                //     this.uploadFlag = false;
                //     this.file = '';
                //     this.importFile();
                // }
            // } catch (error) {
            //     this.uploadFlag = false;
            //     this.clicked = false;
            //     alert(error.response.data.error);
            // }
        }
    }
}
</script>

<style lang="less" scoped>
.mod__user_button{
    padding: 10px 10px 10px 0px;
    display: flex;
    
    .btn-back {
        margin-right: 10px;
    }
}

.mod__btn {
    appearance: none;
    outline: none;
    display: flex;
    align-items: center;
    height: 32px;
    padding-right: 20px;
    padding-left: 20px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    background-color: #fff;
    border: 1px solid #dfe4ea;
    border-radius: 16px;
    transition: color 200ms cubic-bezier(0,0,.58,1);
    will-change: color;
}

.user__detail {
    padding-left: 20px;
    padding-right: 20px;

    &__info {
        display: flex;
        align-items: center;
        padding-top: 20px;

        .user__avatar {
            width: 120px;
            height: 120px;

            img {
                border-radius: 50%;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }
        
        .user__info {
            padding-left: 30px;
            line-height: 1;
            
            .name {
                font-size: 4rem;
            }

            .user__email__phone {
                display: flex;
                font-size: 2rem;
                
                .email {
                    padding-right: 10px;
                }
            }
        }
    }
}

.user-info__detail {
    background-color: #fff;
    border-radius: 5px;
    border: 1px solid #dfe4ea;
    margin-top: 40px;
    
    .user__info {
        display: flex;
        border-bottom: 1px solid #dfe4ea;

        &:last-child {
            border-bottom: none;
        }

        .user-info {
            width: 50%;
            padding-right: 20px;
            padding-left: 20px;
        }
        
        .info-detail {
            width: 50%;

            input[type=text], input[type=password], input[type=file] {
                height: 35px;
                width: 250px;
                border-radius: 5px;
                outline: none;
                border: 1px solid #ddd;
                padding-left: 10px;
            }
        }
    }
}

.tab-user {
    background-color: #f4f4f5;
    position: sticky;
    top: 0;
    z-index: 100000;
    margin-top: 40px;
    padding: 10px 0;

    .action-tab-user, .edit {
        display: flex;

        .btn-cancel__user {
            margin-right: 10px;
        }
    }
}

.import__member {
    padding: 0px 20px;
   .mod__btn {
    font-size: 2rem;
   }
}

.mod__modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 0;
    overflow: hidden;
    opacity: 0;
    transition: opacity, height, z-index;
    transition-delay: 0ms, 200ms, 200ms;
    transition-timing-function: cubic-bezier(0, 0, 1, 1);
    transition-duration: 200ms, 0ms, 0ms;

    label {
        font-size: 1rem;
    }
    
    button {
        line-height: normal !important;
    }
}
  
.mod__modal.is-active {
    z-index: 1000;
    height: 100%;
    overflow: auto;
    opacity: 1;
    transition-delay: 0ms, 0ms, 0ms;
}
  
.mod__modal__overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 120vh;
    background-color: #222529;
    opacity: 0.7;
    transition: opacity;
    transition-timing-function: cubic-bezier(0, 0, 0.58, 1);
    transition-duration: 200ms;
    will-change: opacity;
}
  
.mod__modal__container {
    position: relative;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    min-width: 1020px;
    min-height: 100%;
}
  
.mod__modal__inner {
    position: relative;
    width: calc(100vw - 40px);
    min-width: 980px;
    margin-top: 20px;
    margin-bottom: 20px;
    background-color: #f4f4f5;
    border-radius: 5px;
}
  
.mod__modal__inner.w600 {
    align-self: center;
    width: 600px;
    min-width: 600px;
}
  
.mod__modal__inner.w600 .mod__modal__content {
    height: auto;
}
  
.mod__modal__header {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    height: 60px;
    padding-left: 20px;
    background-color: #fff;
    border-bottom: 1px solid #dfe4ea;
    border-radius: 5px 5px 0 0;
}
  
.mod__modal__header .title {
    margin-right: auto;
    font-size: 2rem;
    font-weight: 700;
}
  
.mod__modal__header__btns {
    display: flex;
    align-items: center;
    margin-right: 20px;
}
  
.mod__modal__header__btns .mod__btn:not(:first-child) {
    margin-left: 10px;
}
  
.mod__modal__header__close {
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
    padding: 0;
    border: 0;
    border-radius: 0;
    outline: none;
    background: transparent;
    color: #333;
    font-family: inherit;
    font-size: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    cursor: pointer;
    border-left: 1px solid #dfe4ea;

    span {
        position: relative;
        height: 15px;
        width: 30px;

        svg {
            position: absolute;
        }
    }
}
  
.mod__modal__content {
    max-height: calc(100vh - 100px);
    overflow: auto;
}
  
.mod__width__resizer {
    position: absolute;
    top: 0;
    right: 0;
    z-index: 20;
    width: 10px;
    height: 100%;
    cursor: ew-resize;
}  

.import__upload__content {
    padding: 30px 20px;
}
  
.import__upload__content .mod__form__parts--checkbox {
    margin-top: 20px;
}
  
.import__upload__file {
    height: 50px;
    display: flex;
    align-items: center;
}
  
.import__upload__file .mod__btn {
    flex-shrink: 0;
    margin-right: 10px;
}
  
.import__upload__file .file__name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.mod__modal__btn__unit {
    display: flex;
    justify-content: center;
    padding-top: 20px;
    padding-bottom: 20px;
    border-top: 1px solid #dfe4ea;
}
  
.mod__modal__btn__unit .mod__btn {
    justify-content: center;
    width: 140px;
    height: 40px;
    margin-right: 5px;
    margin-left: 5px;
    font-size: 2rem;
    border-radius: 20px;
}
</style>
