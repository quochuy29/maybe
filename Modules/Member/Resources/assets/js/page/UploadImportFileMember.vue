<template>
        <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" @click="resetFileUpload" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Launch static backdrop modal
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Import Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="" id="test">
                        <div class="kwt-file">
                            <div class="kwt-file__drop-area">
                                <span class="kwt-file__choose-file ">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                                        <path d="M67.508 468.467c-58.005-58.013-58.016-151.92 0-209.943l225.011-225.04c44.643-44.645 117.279-44.645 161.92 0 44.743 44.749 44.753 117.186 0 161.944l-189.465 189.49c-31.41 31.413-82.518 31.412-113.926.001-31.479-31.482-31.49-82.453 0-113.944L311.51 110.491c4.687-4.687 12.286-4.687 16.972 0l16.967 16.971c4.685 4.686 4.685 12.283 0 16.969L184.983 304.917c-12.724 12.724-12.73 33.328 0 46.058 12.696 12.697 33.356 12.699 46.054-.001l189.465-189.489c25.987-25.989 25.994-68.06.001-94.056-25.931-25.934-68.119-25.932-94.049 0l-225.01 225.039c-39.249 39.252-39.258 102.795-.001 142.057 39.285 39.29 102.885 39.287 142.162-.028A739446.174 739446.174 0 0 1 439.497 238.49c4.686-4.687 12.282-4.684 16.969.004l16.967 16.971c4.685 4.686 4.689 12.279.004 16.965a755654.128 755654.128 0 0 0-195.881 195.996c-58.034 58.092-152.004 58.093-210.048.041z">
                                            <input id="file" @change="uploadFile1($event)" class="demo1 kwt-file__input" name="uploadfile" type="file" placeholder="Select Files" accept=".csv">
                                        </path>
                                    </svg>
                                </span>
                                <input id="file" @change="uploadFile1($event)" class="demo1 kwt-file__input" name="uploadfile" type="file" placeholder="Select Files" accept=".csv">
                                <span class="kwt-file__msg">Select Files</span>
                                <div class="kwt-file__delete"></div>
                            </div>
                        </div>
                        <span>{{ this.file_name }}</span>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="uploadFile" data-bs-dismiss="modal">Upload</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UploadImportFileMember",
        data(){
            return {
                file_name: ''
            }
        },
        methods: {
            resetFileUpload() {
                document.getElementById('test').reset();
                this.file_name = null;
            },
            uploadFile1(e) {
                this.file_name = e.target.files[0].name;
            },
            async uploadFile() {
                try {
                    const formData = new FormData(document.getElementById('test'));
                    formData.append('file', formData.get('uploadfile'));
                    formData.append('delete_user', this.checkDelete);
                    const response = await axios.post(`api/member/upload-file`, formData);
                    if (response !== undefined && response.status === 200) {
                        this.uploadFlag = false;
                        // this.importFile();
                    }
                } catch (error) {
                    this.uploadFlag = false;
                    this.clicked = false;
                    alert(error.response.data.error);
                }
                document.getElementById('test').reset();
            },

            async importFile() {
                try {
                    const response = await axios.post(`member/import`);
                    // this.showToast(response.data.message);
                } catch(error) {
                    const url = error.response.data.url_error;
                    const fileName = error.response.data.file_name;
                    this.downloadErrorLog(url, fileName);
                }
                this.clicked = false;
            },

            downloadErrorLog(url, fileName) {
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            },
        }
    }
</script>

<style lang="less" scoped>
/* Plugin Style Start */
.kwt-file {
	max-width: 466px;
	margin: 0 auto;
}
.kwt-file__drop-area {
	position: relative;
	display: flex;
	align-items: center;
	width: 100%;
	padding: 25px;
	background-color: #ffffff;
	border-radius: 12px;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
	transition: 0.3s;
}
.kwt-file__drop-area.is-active {
	background-color: #d1def0;
}
.kwt-file__choose-file {
	flex-shrink: 0;
	background-color: #1d3557;
	border-radius: 100%;
	margin-right: 10px;
	color: #ffffff;
	width: 48px;
	height: 48px;
	display: flex;
	align-items: center;
	justify-content: center;
}
.kwt-file__choose-file.kwt-file_btn-text {
	border-radius: 4px;
	width: auto;
	height: auto;
	padding: 10px 20px;
	font-size: 14px;
}
.kwt-file__choose-file svg {
	width: 24px;
	height: 24px;
	display: block;
}
.kwt-file__msg {
	color: #1d3557;
	font-size: 16px;
	font-weight: 400;
	line-height: 1.4;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.kwt-file__input {
	position: absolute;
	left: 0;
	top: 0;
	height: 100%;
	width: 100%;
	cursor: pointer;
	opacity: 0;
}
.kwt-file__input:focus {
	outline: none;
}
.kwt-file__delete {
	display: none;
	position: absolute;
	right: 10px;
	width: 18px;
	height: 18px;
	cursor: pointer;
}
.kwt-file__delete:before {
	content: "";
	position: absolute;
	left: 0;
	transition: 0.3s;
	top: 0;
	z-index: 1;
	width: 100%;
	height: 100%;
	background-size: cover;
	background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg fill='%231d3557' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 438.5 438.5'%3e%3cpath d='M417.7 75.7A8.9 8.9 0 00411 73H323l-20-47.7c-2.8-7-8-13-15.4-18S272.5 0 264.9 0h-91.3C166 0 158.5 2.5 151 7.4c-7.4 5-12.5 11-15.4 18l-20 47.7H27.4a9 9 0 00-6.6 2.6 9 9 0 00-2.5 6.5v18.3c0 2.7.8 4.8 2.5 6.6a8.9 8.9 0 006.6 2.5h27.4v271.8c0 15.8 4.5 29.3 13.4 40.4a40.2 40.2 0 0032.3 16.7H338c12.6 0 23.4-5.7 32.3-17.2a64.8 64.8 0 0013.4-41V109.6h27.4c2.7 0 4.9-.8 6.6-2.5a8.9 8.9 0 002.6-6.6V82.2a9 9 0 00-2.6-6.5zm-248.4-36a8 8 0 014.9-3.2h90.5a8 8 0 014.8 3.2L283.2 73H155.3l14-33.4zm177.9 340.6a32.4 32.4 0 01-6.2 19.3c-1.4 1.6-2.4 2.4-3 2.4H100.5c-.6 0-1.6-.8-3-2.4a32.5 32.5 0 01-6.1-19.3V109.6h255.8v270.7z'/%3e%3cpath d='M137 347.2h18.3c2.7 0 4.9-.9 6.6-2.6a9 9 0 002.5-6.6V173.6a9 9 0 00-2.5-6.6 8.9 8.9 0 00-6.6-2.6H137c-2.6 0-4.8.9-6.5 2.6a8.9 8.9 0 00-2.6 6.6V338c0 2.7.9 4.9 2.6 6.6a8.9 8.9 0 006.5 2.6zM210.1 347.2h18.3a8.9 8.9 0 009.1-9.1V173.5c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a8.9 8.9 0 00-9.1 9.1V338a8.9 8.9 0 009.1 9.1zM283.2 347.2h18.3c2.7 0 4.8-.9 6.6-2.6a8.9 8.9 0 002.5-6.6V173.6c0-2.7-.8-4.9-2.5-6.6a8.9 8.9 0 00-6.6-2.6h-18.3a9 9 0 00-6.6 2.6 8.9 8.9 0 00-2.5 6.6V338a9 9 0 002.5 6.6 9 9 0 006.6 2.6z'/%3e%3c/svg%3e");
}
.kwt-file__delete:after {
	content: "";
	position: absolute;
	opacity: 0;
	left: 50%;
	top: 50%;
	width: 100%;
	height: 100%;
	transform: translate(-50%, -50%) scale(0);
	background-color: #1d3557;
	border-radius: 50%;
	transition: 0.3s;
}
.kwt-file__delete:hover:after {
	transform: translate(-50%, -50%) scale(2.2);
	opacity: 0.1;
}
/* Plugin Style End */
</style>
