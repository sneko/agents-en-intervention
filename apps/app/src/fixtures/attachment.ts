import { UppyFile } from '@uppy/core';
import { base64StringToBlob } from 'blob-util';
import { Readable } from 'stream';

import { Attachment as EmailAttachment } from '@aei/app/src/emails/mailer';
import { imageB64Data, imageContentType } from '@aei/app/src/fixtures/image';
import { UiAttachmentSchemaType } from '@aei/app/src/models/entities/attachment';

export const uiAttachments: UiAttachmentSchemaType[] = [
  {
    id: '13422339-278f-400d-9b25-5399e9fe6231',
    url: 'https://via.placeholder.com/300x150',
    contentType: 'image/jpeg',
    size: 16171,
    name: 'sample-1.jpg',
  },
  {
    id: '13422339-278f-400d-9b25-5399e9fe6232',
    url: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
    contentType: 'application/pdf',
    size: 16171,
    name: 'sample-2.pdf',
  },
  {
    id: '13422339-278f-400d-9b25-5399e9fe6233',
    url: 'https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_MP3.mp3',
    contentType: 'audio/mpeg',
    size: 16171,
    name: 'sample-3.mp3',
  },
];

const blob = base64StringToBlob(imageB64Data, imageContentType);

export const uppyFiles: UppyFile[] = [
  {
    source: 'Dashboard',
    id: 'uppy-a/jpg-1e-image/jpeg-16171-1556651302320',
    name: 'sample-1.jpg',
    extension: 'jpg',
    meta: {
      kind: 'AUTHORITY_LOGO',
      relativePath: null,
      name: 'sample-1.jpg',
      type: 'image/jpeg',
    },
    type: 'image/jpeg',
    data: blob,
    progress: {
      uploadStarted: 1676453657332,
      uploadComplete: true,
      percentage: 100,
      bytesUploaded: 16171,
      bytesTotal: 16171,
    },
    size: 16171,
    isRemote: false,
    preview: 'blob:http://localhost:3000/13422339-278f-400d-9b25-5399e9fe623d',
    response: {
      body: {},
      status: 200,
      uploadURL: 'http://localhost:3000/api/upload/e44117d7-ef80-4cd4-bb1a-8378adbf8bb2',
    },
    isPaused: false,
  },
];

export const emailAttachments: EmailAttachment[] = [
  {
    contentType: 'image/jpeg',
    filename: 'sample-1.jpg',
    content: Buffer.from(imageB64Data, 'base64'),
    inline: false,
  },
  {
    contentType: 'image/jpeg',
    filename: 'sample-2.jpg',
    content: Buffer.from(imageB64Data, 'base64'),
    inline: false,
  },
];
