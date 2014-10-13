package ru.dreamkas.jbehave.menuNavigation;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import ru.dreamkas.steps.menu.MenuNavigationSteps;

public class WhenMenuNavigationUserSteps {

    @Steps
    MenuNavigationSteps menuNavigationSteps;

    @When("the user opens menu navigation bar user card")
    public void whenTheUserOpensDashboardUserCard() {
        menuNavigationSteps.userNameLinkClick();
    }

    @When("пользователь нажимает на пункт меню 'Отчеты' в боковом меню навигации")
    public void whenTheUserClicksTheMenuReportItemClick() {
        menuNavigationSteps.reportMenuItemClick();
    }

    @When("the user clicks the menu suppliers item")
    public void whenTheUserClicksTheMenuSuppliersItemClick() {
        menuNavigationSteps.supplierMenuItemClick();
    }

    @When("the user clicks the menu catalog item")
    public void whenTheUserClicksTheMenuCatalogItem() {
        menuNavigationSteps.catalogMenuItemClick();
    }

    @When("the user clicks the menu stores item")
    public void whenTheUserClicksTheMenuStoresItem() {
        menuNavigationSteps.storesMenuItemClick();
    }

    @When("the user clicks the menu stock movement item")
    @Alias("пользователь нажимает на пункт меню Товароджижение в боковом меню навигации")
    public void whenTheUserClicksTheMenuStockMovementItem() {
        menuNavigationSteps.stockMovementMenuItemClick();
    }

    @When("пользователь нажимает на кнопку Запустить кассу в боковом меню навигации")
    public void whenTheUserClicksTheMenuPosLaunchItem() {
        menuNavigationSteps.launchPostButtonClick();
    }
}
