document.addEventListener('DOMContentLoaded', function () {
	const config = document.getElementById('jobapplications-filepond-config');
	if (!config) {
		console.error('jobapplications-filepond-config element not found — FilePond cannot initialize.');
		return;
	}

	const typo3Base = config.dataset.typo3Base;
	const prefix = typo3Base.length > 1 ? typo3Base.replace(/\/$/, '') : typo3Base;
	const uploadUrl = prefix === '/' ? '/jobapplications-upload/' : prefix + '/jobapplications-upload/';
	const revertUrl = prefix === '/' ? '/jobapplications-revert/' : prefix + '/jobapplications-revert/';

	FilePond.registerPlugin(FilePondPluginFileValidateType);
	FilePond.setOptions({
		allowFileTypeValidation     : false,
		server                      : {
			process : {
				url: uploadUrl,
				onerror : (response) => {
					return response;
				}
			},
			revert  : {
				url: revertUrl
			},
			restore : null,
			load    : null,
			fetch   : null
		},
		labelFileProcessingError    : (error) => {
			let returnString = '';
			console.log(error);
			switch (error.body)
			{
				case 'file_size':
					returnString = config.dataset.lblFileSize;
					break;
				case 'file_type':
					returnString = config.dataset.lblFileType;
					break;
				case 'file_error':
					returnString = config.dataset.lblFileError;
					break;
				default:
					if (error.code === 500)
					{
						returnString = config.dataset.lblServerError;
					}
					else
					{
						returnString = config.dataset.lblFileError;
					}
					break;
			}
			return returnString;
		},
		fileValidateTypeDetectType  : (source, type) => new Promise((resolve, reject) => {
			if (type === '')
			{
				reject(type)
			}
			// Do custom type detection here and return with promise
			resolve(type);
		}),
		labelIdle                   : config.dataset.lblIdleDrag + ' <span class="filepond--label-action">' + config.dataset.lblIdleBrowse + ' </span>',
		labelFileLoading            : config.dataset.lblLoading,
		labelFileLoadError          : config.dataset.lblLoadingError,
		labelFileProcessing         : config.dataset.lblUploading,
		labelTapToCancel            : config.dataset.lblCancel,
		labelTapToRetry             : config.dataset.lblRetry,
		labelFileProcessingComplete : config.dataset.lblComplete,
		labelTapToUndo              : config.dataset.lblUndo
	});
	FilePond.parse(document.body);
});
