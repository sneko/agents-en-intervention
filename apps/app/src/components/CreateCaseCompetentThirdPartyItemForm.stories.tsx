import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm } from '@aei/docs/.storybook/testing';
import { CreateCaseCompetentThirdPartyItemForm } from '@aei/app/src/components/CreateCaseCompetentThirdPartyItemForm';
import { caseCompetentThirdParties } from '@aei/app/src/fixtures/case';
import { CreateCaseCompetentThirdPartyItemPrefillSchema } from '@aei/app/src/models/actions/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof CreateCaseCompetentThirdPartyItemForm;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();
export default {
  title: 'Forms/CreateCaseCompetentThirdPartyItem',
  component: CreateCaseCompetentThirdPartyItemForm,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['createCaseCompetentThirdPartyItem'],
        response: {
          item: caseCompetentThirdParties[0],
        },
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <CreateCaseCompetentThirdPartyItemForm {...args} />;
};

const EmptyStory = Template.bind({});
EmptyStory.args = {
  availableParentItems: caseCompetentThirdParties.filter((item) => !item.parentId),
};
EmptyStory.parameters = { ...defaultMswParameters };
EmptyStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Empty = prepareStory(EmptyStory);

const FilledStory = Template.bind({});
FilledStory.args = {
  ...EmptyStory.args,
  prefill: CreateCaseCompetentThirdPartyItemPrefillSchema.parse({
    parentId: caseCompetentThirdParties[1].id,
    name: 'My new competent third-party',
  }),
};
FilledStory.parameters = { ...defaultMswParameters };
FilledStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Filled = prepareStory(FilledStory);
