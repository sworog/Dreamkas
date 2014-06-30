package project.lighthouse.autotests.pages.commercialManager.bankAccounts;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

public class BankAccountsListPage extends CommonPageObject {
    public BankAccountsListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void createNewBankAccountLinkClick() {
        clickByContainsTextLink("Добавить расчетный счет");
    }

    public void bankAccountListItemByBankAndAccountClick(String bankName, String account) {
        // TODO: как-то находим и кликаем
    }

    public void bankAccountListItemByBankClick(String bankName) {
        click(By.xpath("//tr/td[contains(text(), '" + bankName + "')]"));
    }
}
