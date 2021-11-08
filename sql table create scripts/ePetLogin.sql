USE [PHPLogin_Dev]
GO
	/****** Object:  Table [dbo].[ePetLogin]    Script Date: 17/09/2021 08:38:01 ******/
SET
	ANSI_NULLS ON
GO
SET
	QUOTED_IDENTIFIER ON
GO
	CREATE TABLE [dbo].[ePetLogin](
		[userid] [int] IDENTITY(1, 1) NOT NULL,
		[email] [nvarchar](255) NOT NULL,
		[name] [nvarchar](300) NULL,
		[address] [nvarchar](300) NULL,
		[postcode] [nvarchar](12) NULL,
		[phonenumber] [nvarchar](72) NULL,
		[mobilenumber] [nvarchar](72) NULL,
		[password] [nvarchar](255) NULL,
		[connection] [nvarchar](50) NULL,
		[activatekey] [nvarchar](50) NULL,
		[resetkey] [nvarchar](50) NULL,
		[accountcreated] [datetime] NULL,
		[accountactivated] [datetime] NULL,
		[accountdisabled] [datetime] NULL,
		[lastloggedin] [datetime] NULL,
		[userlevel] [int] NULL,
		[accountstatus] [nvarchar](25) NULL,
		[logonattempts] [int] NULL,
		[safelock] [int] NULL
	) ON [PRIMARY]
GO