package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.authorization.AuthorizationPage;
import project.lighthouse.autotests.pages.commercialManager.product.ProductCardView;
import project.lighthouse.autotests.pages.commercialManager.product.ProductListPage;

public class AuthorizationSteps extends ScenarioSteps {

    AuthorizationPage authorizationPage;
    ProductCardView productCardView;
    ProductListPage productListPage;

    public AuthorizationSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void authorization(String userName) {
        authorizationPage.authorization(userName);
    }

    @Step
    public void authorization(String userName, String password) {
        authorizationPage.authorization(userName, password);
    }

    @Step
    public void logOut() {
        authorizationPage.logOut();
    }

    @Step
    public void beforeScenario() {
        authorizationPage.beforeScenario();
    }

    @Step
    public void checkUser(String userName) {
        authorizationPage.checkUser(userName);
    }

    @Step
    public void openPage() {
        authorizationPage.open();
    }

    @Step
    public void loginFormIsPresent() {
        authorizationPage.loginFormIsPresent();
    }

    @Step
    public void authorizationFalse(String userName, String password) {
        authorizationPage.authorizationFalse(userName, password);
    }

    @Step
    public void error403IsPresent() {
        authorizationPage.error403IsPresent();
    }

    @Step
    public void error403IsNotPresent() {
        authorizationPage.error403IsNotPresent();
    }

    @Step
    public void editProductButtonIsNotPresent() {
        try {
            productCardView.editButtonClick();
            throw new AssertionError("Edit product link is present!");
        } catch (Exception e) {
        }
    }

    @Step
    public void newProductCreateButtonIsNotPresent() {
        try {
            productListPage.createNewProductButtonClick();
            throw new AssertionError("Create new product button is present on product list page!");
        } catch (Exception e) {
        }
    }
}
