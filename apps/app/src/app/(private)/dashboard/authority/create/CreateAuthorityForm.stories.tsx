import { generateMock } from '@anatine/zod-mock';
import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { CreateAuthorityForm, CreateAuthorityFormContext } from '@aei/app/src/app/(private)/dashboard/authority/create/CreateAuthorityForm';
import { Normal as UploaderNormalStory } from '@aei/app/src/components/uploader/Uploader.stories';
import { CreateAuthorityPrefillSchema } from '@aei/app/src/models/actions/authority';
import { AuthoritySchema } from '@aei/app/src/models/entities/authority';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof CreateAuthorityForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();
export default {
  title: 'Forms/CreateAuthority',
  component: CreateAuthorityForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['createAuthority'],
        response: {
          ...generateMock(AuthoritySchema),
          logoAttachmentId: 'd58ac4a3-7672-403c-ad04-112f5927e2be',
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <CreateAuthorityForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory, {
  childrenContext: {
    context: CreateAuthorityFormContext,
    value: {
      ContextualUploader: UploaderNormalStory,
    },
  },
});

const FilledStory = Template.bind({});
FilledStory.args = {
  prefill: CreateAuthorityPrefillSchema.parse({
    type: 'REGION',
    name: 'Bretagne',
    slug: 'my-bzh',
    logoAttachmentId: 'd58ac4a3-7672-403c-ad04-112f5927e2be',
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory, {
  childrenContext: {
    context: CreateAuthorityFormContext,
    value: {
      ContextualUploader: UploaderNormalStory,
    },
  },
});
