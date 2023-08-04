/**
 * @jest-environment node
 */
import { useServerTranslation } from '@aei/app/src/i18n';
import { extractCaseHumanIdFromEmailFactory, getCaseEmailFactory } from '@aei/app/src/utils/business/case';

describe('extractCaseHumanIdFromEmail()', () => {
  const { t } = useServerTranslation('common');

  const domain = 'agents-en-intervention.local.fr';
  const getCaseEmail = getCaseEmailFactory(domain);
  const extractCaseHumanIdFromEmail = extractCaseHumanIdFromEmailFactory(domain);

  it('should extract something', async () => {
    const caseHumanId = extractCaseHumanIdFromEmail('dossier-41@aei.local.fr');

    expect(caseHumanId).toBe('41');
  });

  it('should not extract anything', async () => {
    const caseHumanId = extractCaseHumanIdFromEmail('thomas@aei.fr');

    expect(caseHumanId).toBeNull();
  });

  it('should combine correctly with its opposite', async () => {
    const originalEmail = 'dossier-41@aei.local.fr';

    const caseHumanId = extractCaseHumanIdFromEmail('dossier-41@aei.local.fr');

    expect(caseHumanId).not.toBeNull();

    const generatedEmail = getCaseEmail(t, caseHumanId as string);

    expect(generatedEmail).toBe(originalEmail);
  });
});
