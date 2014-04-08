package project.lighthouse.autotests.jbehave.elements.autocomplete;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.elements.autocomplete.AutocompleteSteps;

/**
 * Jbehave reusable THEN scenario steps for interacting with autocomplete results
 */
public class ThenAutocompleteUserSteps {

    @Steps
    AutocompleteSteps autocompleteSteps;

    @Then("the user checks the autocomplete result list contains exact entries $examplesTable")
    public void thenTheUserChecksTheAutocompleteResultListContainsExactEntries(ExamplesTable examplesTable) {
        autocompleteSteps.exactCompare(examplesTable);
    }

    @Then("the user checks the autocomplete result entry found by name '$name' is highlighted")
    public void thenTheUserChecksTheAutoCompleteResultEntryFoundByNameIsHighlighted(String name) {
        autocompleteSteps.assertAutoCompleteResultIsHighlighted(name);
    }

    @Then("the user checks the autocomplete result entry found by name '$name' highlighted text is '$expectedValue'")
    @Alias("the user checks the autocomplete result entry found by name '$name' highlighted text is expectedValue")
    public void thenTheUserChecksTheAutocompleteResultEntryFoundByNameHighlightedText(String name, String expectedValue) {
        autocompleteSteps.assertAutoCompleteResultHighlightText(name, expectedValue);
    }
}
