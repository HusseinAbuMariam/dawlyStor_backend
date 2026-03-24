import { useCallback, useRef, useState } from 'react'
import { FilePond, registerPlugin } from 'react-filepond'
import 'filepond/dist/filepond.min.css'

// Import FilePond plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
import FilePondPluginImageResize from 'filepond-plugin-image-resize'
import FilePondPluginImageCrop from 'filepond-plugin-image-crop'

// Import FilePond styles
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css'


import { Label } from '@/components/ui/label'
import InputError from '@/components/shared/input-error'
import { cn } from '@/lib/utils'
import { Media } from '@/types/dashboard'

// Register the plugins - must be called before using FilePond component
registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageResize,
    FilePondPluginImageCrop,
)

interface FileUploadProps {
    name: string
    label?: string
    multiple?: boolean
    acceptedFileTypes?: string[]
    maxFiles?: number
    maxFileSize?: string
    files?: Media[] | null
    error?: string
    className?: string
    required?: boolean
    aspectRatio?: string
}

const getCsrfToken = () => {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) return metaToken;
    return '';
}

export default function FileUpload({
    name,
    label,
    multiple = false,
    acceptedFileTypes = ['image/*'],
    maxFiles = 10,
    maxFileSize = '10MB',
    aspectRatio = '1:1',
    files: initialFiles = [],
    error,
    className,
    required = false,
}: FileUploadProps) {
    const getInitialFiles = useCallback(() => {
        if (!initialFiles) return [];
        return initialFiles.map((file) => ({
            source: String(file.id),
            options: { type: 'local' },
        }))
    }, [initialFiles])

    const [files, setFiles] = useState<any[]>(getInitialFiles);
    const [tempFileIds, setTempFileIds] = useState<string[]>([]);

    const handleProcessFile = useCallback((error: any, file: any) => {
        if (file.serverId) {
            setTempFileIds(prev => [...prev, file.serverId])
        }
    }, [tempFileIds])

    const handleRemoveFile = useCallback((error: any, file: any) => {
        setTempFileIds(prev => prev.filter((id: string) => id !== String(file.source)))
    }, [tempFileIds])

    return (
        <div className={cn('space-y-2', className, 'filepond-wrapper')}>
            {label && (
                <Label htmlFor={name}>
                    {label}
                    {required && <span className="text-destructive ml-1">*</span>}
                </Label>
            )}
            <FilePond
                name={name}
                files={files}
                allowMultiple={multiple}
                onprocessfile={handleProcessFile}
                onupdatefiles={setFiles}
                onremovefile={handleRemoveFile}
                maxFiles={maxFiles}
                acceptedFileTypes={acceptedFileTypes}
                server={{
                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                        const formData = new FormData()
                        formData.append(fieldName, file, file.name)

                        const request = new XMLHttpRequest()
                        request.open('POST', '/upload-temp')
                        request.setRequestHeader('X-CSRF-TOKEN', getCsrfToken())

                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total)
                        }

                        request.onload = () => {
                            if (request.status >= 200 && request.status < 300) {
                                try {
                                    const data = JSON.parse(request.responseText)
                                    load(data.media_id)
                                } catch {
                                    load(request.responseText)
                                }
                            } else {
                                error('Upload failed')
                            }
                        }

                        request.onerror = () => error('Upload failed')
                        request.send(formData)

                        return {
                            abort: () => {
                                request.abort()
                                abort()
                            },
                        }
                    },

                    revert: async (uniqueFileId, load, error) => {
                        try {
                            await fetch('/revert-temp', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': getCsrfToken(),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'text/plain',
                                },
                                body: uniqueFileId,
                            })
                            load()
                        } catch {
                            error('Revert failed')
                        }
                    },

                    load: '/load-file/',

                    remove: async (source, load, error) => {
                        try {
                            await fetch(`/remove-file/${source}`, {
                                headers: {
                                    'X-CSRF-TOKEN': getCsrfToken(),
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                method: 'DELETE'
                            })
                            load()
                        } catch (e) {
                            if (error) error('Failed to remove file')
                        }
                    }
                }}
                labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
                labelFileProcessing='Uploading...'
                labelFileProcessingComplete='Upload complete'
                labelFileProcessingError='Error during upload'
                labelFileProcessingAborted='Upload cancelled'
                labelFileRemoveError='Error during remove'
                labelTapToCancel='tap to cancel'
                labelTapToRetry='tap to retry'
                labelTapToUndo='tap to undo'
                labelButtonRemoveItem='Remove'
                labelButtonAbortItemLoad='Abort'
                labelButtonRetryItemLoad='Retry'
                labelButtonAbortItemProcessing='Cancel'
                labelButtonUndoItemProcessing='Undo'
                labelButtonProcessItem='Upload'

                styleButtonRemoveItemPosition="right"
                styleButtonProcessItemPosition="right"

                // Enable image preview, resize, and crop
                // Note: These plugins process images automatically on upload
                // They don't show interactive edit buttons - images are processed according to settings below
                allowImagePreview={true}
                allowImageResize={true}
                allowImageCrop={true}

                // Image preview settings
                imagePreviewHeight={200}
                imagePreviewMinHeight={100}
                imagePreviewMaxHeight={300}

                // Image crop settings
                // Leave imageCropAspectRatio undefined for free cropping
                // Or set to "1:1", "16:9", etc. for fixed aspect ratio
                imageCropAspectRatio={aspectRatio}

                // Image resize settings
                // Images will be resized to these dimensions before upload
                imageResizeTargetWidth={1200}
                imageResizeTargetHeight={1200}
                imageResizeMode="contain"
                imageResizeUpscale={false}

                credits={false}
            />

            <input
                type="hidden"
                name={'temp_ids'}
                value={tempFileIds}
            />
            {error && <InputError message={error} />}
        </div>
    )
}

