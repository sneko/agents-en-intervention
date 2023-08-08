import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { EditCaseDomainItemForm } from '@aei/app/src/components/EditCaseDomainItemForm';
import { caseDomains } from '@aei/app/src/fixtures/case';
import { EditCaseDomainItemPrefillSchema } from '@aei/app/src/models/actions/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof EditCaseDomainItemForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();
export default {
  title: 'Forms/EditCaseDomainItem',
  component: EditCaseDomainItemForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['editCaseDomainItem'],
        response: {
          item: caseDomains[0],
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <EditCaseDomainItemForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {
  availableParentItems: caseDomains.filter((item) => !item.parentId),
  prefill: EditCaseDomainItemPrefillSchema.parse({
    itemId: caseDomains[0].id,
  }),
};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  ...EmptyStory.args,
  prefill: EditCaseDomainItemPrefillSchema.parse({
    ...EmptyStory.args.prefill,
    parentId: caseDomains[1].id,
    name: 'My updated domain',
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
