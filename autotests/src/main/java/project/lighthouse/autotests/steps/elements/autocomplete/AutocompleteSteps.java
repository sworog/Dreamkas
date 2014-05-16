package project.lighthouse.autotests.steps.elements.autocomplete;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.annotations.findby.By;
import net.thucydides.core.steps.ScenarioSteps;
import org.hamcrest.Matchers;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import project.lighthouse.autotests.elements.preLoader.AutocompletePreLoader;
import project.lighthouse.autotests.objects.web.autocomplete.AutoCompleteResult;
import project.lighthouse.autotests.objects.web.autocomplete.AutoCompleteResultCollection;
import project.lighthouse.autotests.objects.web.compare.CompareResult;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

/**
 * Reusable steps for interacting with autocomplete results
 */
public class AutocompleteSteps extends ScenarioSteps {

    private AutoCompleteResultCollection getAutoCompleteResultCollection() {
        new AutocompletePreLoader(getDriver()).await();
        return new AutoCompleteResultCollection(getDriver(), By.xpath("//*[contains(@class, 'autocomplete__item') or @class='autocomplete__hint']"));
    }

    @Step
    public void exactCompare(ExamplesTable examplesTable) {
        getAutoCompleteResultCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void assertAutoCompleteResultIsHighlighted(String locator) {
        locator = CompareResults.normalizeExpectedValue(locator);
        Boolean isHighlighted = ((AutoCompleteResult) getAutoCompleteResultCollection()
                .getAbstractObjectByLocator(locator))
                .isHighlighted();
        String message = String.format("The result with name '%s' is not highlighted", locator);
        Assert.assertThat(
                message,
                isHighlighted,
                Matchers.equalTo(true)
        );
    }

    @Step
    public void assertAutoCompleteResultHighlightText(String locator, String expectedValue) {
        locator = CompareResults.normalizeExpectedValue(locator);
        String highlightText = ((AutoCompleteResult) getAutoCompleteResultCollection()
                .getAbstractObjectByLocator(locator)).getHighlightedText();
        Assert.assertThat(
                highlightText,
                Matchers.equalTo(expectedValue));
    }

    @Step
    public void autocompleteResultClickByName(String locator) {
        locator = CompareResults.normalizeExpectedValue(locator);
        ((AutoCompleteResult) getAutoCompleteResultCollection()
                .getAbstractObjectByLocator(locator))
                .click();
    }

    @Step
    public void hoverOverAutocompleteResult(String locator) {
        locator = CompareResults.normalizeExpectedValue(locator);
        WebElement autocompleteWebElement = getAutoCompleteResultCollection()
                .getAbstractObjectByLocator(locator)
                .getElement();
        new Actions(getDriver()).moveToElement(autocompleteWebElement, 0, 0).build().perform();
    }
}
