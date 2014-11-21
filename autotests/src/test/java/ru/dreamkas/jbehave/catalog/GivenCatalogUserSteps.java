package ru.dreamkas.jbehave.catalog;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import ru.dreamkas.steps.catalog.CatalogSteps;

public class GivenCatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @Given("the user opens catalog page")
    @Alias("пользователь открывает страницу ассортимента")
    public void givenTheUserOpensCatalogPage() {
        catalogSteps.openCatalogPage();
    }
}
