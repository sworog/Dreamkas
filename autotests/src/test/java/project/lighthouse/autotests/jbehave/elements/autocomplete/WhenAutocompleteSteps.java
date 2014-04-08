package project.lighthouse.autotests.jbehave.elements.autocomplete;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.elements.autocomplete.AutocompleteSteps;

/**
 * Jbehave reusable WHEN scenario steps for interacting with autocomplete results
 */
public class WhenAutocompleteSteps {

    @Steps
    AutocompleteSteps autocompleteSteps;

    @When("the user clicks on the autocomplete result with name '$name'")
    public void whenTheUserClicksOnTheAutocompleteResultWithName(String name) {
        autocompleteSteps.autocompleteResultClickByName(name);
    }

    @When("the user hovers mouse over autocomplete result entry found by name '$name'")
    public void whenTheUserHoversMouseOverAutocompleteResultEntry(String name) {
        autocompleteSteps.hoverOverAutocompleteResult(name);
    }
}
