import { Meta, StoryFn } from '@storybook/react';

import { ComponentProps, StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindForm, playFindFormInMain } from '@aei/docs/.storybook/testing';
import { AsMainAgent as PrivateLayoutAsMainAgentStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import { Empty as AddNoteFormEmptyStory } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/case/[caseId]/AddNoteForm.stories';
import { CasePage, CasePageContext } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/case/[caseId]/CasePage';
import { Normal as CaseCompetentThirdPartyFieldNormalStory } from '@aei/app/src/components/CaseCompetentThirdPartyField.stories';
import { Normal as CaseDomainFieldNormalStory } from '@aei/app/src/components/CaseDomainField.stories';
import { Normal as NoteCardNormalStory } from '@aei/app/src/components/NoteCard.stories';
import { Normal as MessengerNormalStory } from '@aei/app/src/components/messenger/Messenger.stories';
import { Normal as UploaderNormalStory } from '@aei/app/src/components/uploader/Uploader.stories';
import { cases, casesWrappers } from '@aei/app/src/fixtures/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof CasePage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/Case',
  component: CasePage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const mswListCasesParameters = {
  type: 'query' as 'query',
  path: ['getCase'] as ['getCase'],
  response: {
    caseWrapper: {
      ...casesWrappers[0],
      case: {
        ...casesWrappers[0].case,
        termReminderAt: new Date('December 20, 2022 03:24:00 UTC'),
      },
    },
  },
};

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock(mswListCasesParameters),
      getTRPCMock({
        type: 'mutation',
        path: ['updateCase'],
        response: { caseWrapper: casesWrappers[0] },
      }),
      getTRPCMock({
        type: 'mutation',
        path: ['generatePdfFromCase'],
        response: {
          attachment: {
            id: '13422339-278f-400d-9b25-5399e9fe6232',
            url: 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
          },
        },
      }),
    ],
  },
};

const commonComponentProps: ComponentProps<ComponentType> = {
  params: {
    authorityId: casesWrappers[0].case.authorityId,
    caseId: casesWrappers[0].case.id,
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <CasePage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  ...commonComponentProps,
};
NormalStory.parameters = { ...defaultMswParameters };
NormalStory.play = async ({ canvasElement }) => {
  await playFindForm(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: CasePageContext,
    value: {
      ContextualNoteCard: NoteCardNormalStory,
      ContextualAddNoteForm: AddNoteFormEmptyStory,
      ContextualUploader: UploaderNormalStory,
      ContextualCaseDomainField: CaseDomainFieldNormalStory,
      ContextualCaseCompetentThirdPartyField: CaseCompetentThirdPartyFieldNormalStory,
      ContextualMessenger: MessengerNormalStory,
    },
  },
});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {
  ...commonComponentProps,
};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
  ...defaultMswParameters,
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindFormInMain(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsMainAgentStory,
  childrenContext: {
    context: CasePageContext,
    value: {
      ContextualNoteCard: NoteCardNormalStory,
      ContextualAddNoteForm: AddNoteFormEmptyStory,
      ContextualUploader: UploaderNormalStory,
      ContextualCaseDomainField: CaseDomainFieldNormalStory,
      ContextualCaseCompetentThirdPartyField: CaseCompetentThirdPartyFieldNormalStory,
      ContextualMessenger: MessengerNormalStory,
    },
  },
});
