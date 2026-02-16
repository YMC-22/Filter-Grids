# 🏗️ Structural Layout Builder

A powerful, visual grid-based constructor for WordPress designed to create complex, responsive card layouts with ease.

---

## 🚀 Overview

The **Structural Layout Builder** bridges the gap between simple content entry and high-end design. It allows users to move beyond linear "Classic" layouts and build sophisticated structures using a nested hierarchy of Rows and Columns.

## 🛠️ Builder Modes

The tool operates in two distinct modes to suit different workflow needs:

### 1. Classic Mode
* **Best for:** Simple, vertically stacked content.
* **Logic:** Elements follow a standard top-to-bottom flow.

### 2. Structural Builder Mode
* **Best for:** Complex, multi-dimensional layouts.
* **Logic:** Unlocks the grid engine. You can nest elements within **Rows** and **Columns** for advanced positioning.

---

## ✨ Getting Started: Initialization Modes

When you first activate the Structural Builder, you can choose how to initialize your workspace. This choice determines your starting point:

### 🎨 Start from preset
* **The "Fast Track" option.**
* Choose from a library of pre-designed templates.
* **Use case:** Ideal for maintaining design consistency across projects or when you need a professional layout in seconds.

### 🔄 Start from current Classic layout
* **The "Migration" option.**
* Automatically captures your existing elements from the Classic Mode and wraps them into a valid Row/Column grid.
* **Use case:** Best for upgrading existing posts to a structural layout without losing any previously entered data.

### ⬜ Start from scratch
* **The "Blank Canvas" option.**
* Opens a completely empty workspace with a single empty row.
* **Use case:** For designers who want 100% creative freedom to build their own unique hierarchy from the ground up.

> [!TIP]
> **Not sure which one to pick?** > *Start from current Classic layout* is the safest way to experiment with your existing content, while *Start from preset* is the best way to see what the builder is truly capable of.

---

## 🏗️ Understanding the Hierarchy

To build effectively, keep this "Tree" structure in mind:

1.  **Row**: The horizontal container that holds your grid.
2.  **Column**: Sits inside a Row. You can place multiple columns side-by-side.
3.  **Element**: The content (Images, Headings, Buttons) that lives inside a Column.

---

## 🖱️ Interface & Actions

The **Actions** panel in the sidebar is your command center. Each button is semantically designed for quick recognition.

| Icon | Action | Description |
| :--- | :--- | :--- |
| `+` | **Add** | Inserts a new Row, Column, or Element into the selected node. |
| `❐` | **Duplicate** | Creates an exact clone of the selected item and its settings. |
| `↑` | **Move Up** | Shifts the item one position higher within its parent. |
| `↓` | **Move Down** | Shifts the item one position lower within its parent. |
| `🗑` | **Delete** | Removes the item. *Warning: Deleting a Row removes all nested content.* |

---

## 📖 Step-by-Step Guide

### 1. Initial Setup
Enable the **Structural Builder** option in the settings panel. Select one of the three initialization modes described above.

### 2. Building a Grid
* Click **Add Row** to create your first horizontal section.
* Inside the row, click **Add Column**. You can add up to 4 columns to create split layouts.
* Add your elements (Heading, Image, ACF Fields) directly into the columns.

### 3. Organizing
Use the **Move Up/Down** buttons to reorder elements. The live preview updates instantly, allowing you to see the hierarchy changes in real-time.

---

## ⚠️ Important Considerations

* **Responsiveness**: While the builder is flexible, try to limit nesting to 2-3 levels to ensure your layout remains mobile-friendly.
* **Backups**: Before performing major structural changes on an existing layout, it is recommended to save a draft of your post.

---

## 🔗 Resources
- **Support**: [Open an Issue](https://github.com/your-repo/issues)
- **Tutorials**: [Video Guides Coming Soon]