package ru.dreamkas.common.pageObjects;

public interface ModalWindowPageObject extends GeneralPageObject {

    public String modalWindowXpath();

    public void confirmationOkClick();

    public void deleteButtonClick();

    public void confirmDeleteButtonClick();
}
