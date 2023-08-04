import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { CaseCompetentThirdPartyField, CaseCompetentThirdPartyFieldContext } from '@aei/app/src/components/CaseCompetentThirdPartyField';
import { Empty as CreateCaseCompetentThirdPartyItemFormEmptyStory } from '@aei/app/src/components/CreateCaseCompetentThirdPartyItemForm.stories';
import { Empty as EditCaseCompetentThirdPartyItemFormEmptyStory } from '@aei/app/src/components/EditCaseCompetentThirdPartyItemForm.stories';
import { caseCompetentThirdParties } from '@aei/app/src/fixtures/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof CaseCompetentThirdPartyField;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/CaseCompetentThirdPartyField',
  component: CaseCompetentThirdPartyField,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'query',
        path: ['getCaseCompetentThirdPartyItems'],
        response: {
          items: caseCompetentThirdParties.reverse(), // Reverse to check UI sorting
        },
      }),
      getTRPCMock({
        type: 'mutation',
        path: ['deleteCaseCompetentThirdPartyItem'],
        response: undefined,
      }),
    ],
  },
};

async function playFindCombobox(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await within(canvasElement).findByRole('combobox', {
    name: /entité/i,
  });
}

const Template: StoryFn<ComponentType> = (args) => {
  return <CaseCompetentThirdPartyField {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  onChange: () => {},
};
NormalStory.parameters = {
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await playFindCombobox(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: CaseCompetentThirdPartyFieldContext,
    value: {
      ContextualCreateCaseCompetentThirdPartyItemForm: CreateCaseCompetentThirdPartyItemFormEmptyStory,
      ContextualEditCaseCompetentThirdPartyItemForm: EditCaseCompetentThirdPartyItemFormEmptyStory,
    },
  },
});

const EditModeStory = Template.bind({});
EditModeStory.args = {
  onChange: () => {},
  editMode: true,
};
EditModeStory.parameters = {
  ...defaultMswParameters,
};
EditModeStory.play = async ({ canvasElement }) => {
  await playFindCombobox(canvasElement);
};

export const EditMode = prepareStory(EditModeStory, {
  childrenContext: {
    context: CaseCompetentThirdPartyFieldContext,
    value: {
      ContextualCreateCaseCompetentThirdPartyItemForm: CreateCaseCompetentThirdPartyItemFormEmptyStory,
      ContextualEditCaseCompetentThirdPartyItemForm: EditCaseCompetentThirdPartyItemFormEmptyStory,
    },
  },
});

const LoadingErrorStory = Template.bind({});
LoadingErrorStory.args = {
  onChange: () => {},
};
LoadingErrorStory.parameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'query',
        path: ['getCaseCompetentThirdPartyItems'],
        response: new Error('failure') as any,
      }),
    ],
  },
};
LoadingErrorStory.play = async ({ canvasElement }) => {
  await playFindCombobox(canvasElement);
};

export const LoadingError = prepareStory(LoadingErrorStory, {
  childrenContext: {
    context: CaseCompetentThirdPartyFieldContext,
    value: {
      ContextualCreateCaseCompetentThirdPartyItemForm: CreateCaseCompetentThirdPartyItemFormEmptyStory,
      ContextualEditCaseCompetentThirdPartyItemForm: EditCaseCompetentThirdPartyItemFormEmptyStory,
    },
  },
});
