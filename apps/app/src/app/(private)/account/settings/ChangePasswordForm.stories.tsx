import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { ChangePasswordForm } from '@aei/app/src/app/(private)/account/settings/ChangePasswordForm';
import { ChangePasswordPrefillSchema } from '@aei/app/src/models/actions/auth';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof ChangePasswordForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Forms/ChangePassword',
  component: ChangePasswordForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['changePassword'],
        response: undefined,
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <ChangePasswordForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {
  prefill: ChangePasswordPrefillSchema.parse({}),
};
EmptyStory.parameters = { ...defaultMswParameters };

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  prefill: ChangePasswordPrefillSchema.parse({
    currentPassword: 'MyCurrentPassword@1',
    newPassword: 'MyNewPassword@1',
  }),
};
FilledStory.parameters = { ...defaultMswParameters };

export const Filled = prepareStory(FilledStory);
