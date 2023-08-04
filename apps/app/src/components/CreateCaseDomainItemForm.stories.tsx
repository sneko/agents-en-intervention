import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { CreateCaseDomainItemForm } from '@aei/app/src/components/CreateCaseDomainItemForm';
import { caseDomains } from '@aei/app/src/fixtures/case';
import { CreateCaseDomainItemPrefillSchema } from '@aei/app/src/models/actions/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof CreateCaseDomainItemForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();
export default {
  title: 'Forms/CreateCaseDomainItem',
  component: CreateCaseDomainItemForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['createCaseDomainItem'],
        response: {
          item: caseDomains[0],
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <CreateCaseDomainItemForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {
  availableParentItems: caseDomains.filter((item) => !item.parentId),
};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  ...EmptyStory.args,
  prefill: CreateCaseDomainItemPrefillSchema.parse({
    parentId: caseDomains[1].id,
    name: 'My new domain',
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
