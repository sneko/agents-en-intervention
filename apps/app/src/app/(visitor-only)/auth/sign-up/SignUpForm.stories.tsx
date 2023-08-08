import { generateMock } from '@anatine/zod-mock';
import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { SignUpForm } from '@aei/app/src/app/(visitor-only)/auth/sign-up/SignUpForm';
import { SignUpPrefillSchema } from '@aei/app/src/models/actions/auth';
import { UserSchema } from '@aei/app/src/models/entities/user';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof SignUpForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Forms/SignUp',
  component: SignUpForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['signUp'],
        response: {
          user: generateMock(UserSchema),
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <SignUpForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {
  prefill: SignUpPrefillSchema.parse({
    invitationToken: 'abc',
  }),
};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  prefill: SignUpPrefillSchema.parse({
    invitationToken: 'abc',
    email: 'jean@france.fr',
    password: 'Mypassword@1',
    firstname: 'Jean',
    lastname: 'Derrien',
    termsAccepted: true,
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
